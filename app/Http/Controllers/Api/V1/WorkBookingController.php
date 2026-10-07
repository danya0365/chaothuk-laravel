<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BookingStatus;
use App\Enums\ConfirmStatus;
use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Resources\Api\V1\WorkBookingCollection;
use App\Http\Resources\Api\V1\WorkBookingResource;
use App\Models\UserNotification;
use App\Models\Work;
use App\Models\WorkBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Bookings of works.
 *
 * Two sides meet here: the customer who requested the work, and the worker who owns
 * the listing. Which side you are decides what you may do, so every action resolves
 * the caller against the booking before touching it.
 */
class WorkBookingController extends Controller
{
    /**
     * Bookings the caller made as a customer.
     */
    public function indexAsCustomer(Request $request): WorkBookingCollection
    {
        $bookings = WorkBooking::query()
            ->with(['work.province', 'work.workType', 'author'])
            ->whereBelongsTo($request->user(), 'author')
            ->orderByDesc('created_at')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50))
            ->withQueryString();

        return WorkBookingCollection::make($bookings);
    }

    /**
     * Bookings made against the caller's own work listings.
     */
    public function indexAsOwner(Request $request): WorkBookingCollection
    {
        $bookings = WorkBooking::query()
            ->with(['work.province', 'work.workType', 'author'])
            ->whereHas('work', fn ($query) => $query->whereBelongsTo($request->user(), 'author'))
            ->orderByDesc('created_at')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50))
            ->withQueryString();

        return WorkBookingCollection::make($bookings);
    }

    public function show(Request $request, WorkBooking $workBooking): WorkBookingResource
    {
        $this->assertParticipant($request, $workBooking);

        return new WorkBookingResource($workBooking->load(['work.province', 'work.workType', 'author']));
    }

    /**
     * Book a work.
     *
     * @throws AccessDeniedHttpException when booking your own listing
     */
    public function store(StoreBookingRequest $request, Work $work): JsonResponse
    {
        $user = $request->user();

        // The old API never checked this, so anyone could book their own listing.
        if ($work->author_id === $user->id) {
            throw new AccessDeniedHttpException('ไม่สามารถจองงานของตัวเองได้');
        }

        if ($work->work_status !== \App\Enums\WorkStatus::STAND_BY->value) {
            throw new AccessDeniedHttpException('ประกาศนี้ไม่เปิดรับการจองแล้ว');
        }

        $post = $request->validated();

        $booking = WorkBooking::create([
            'booking_date' => $post['booking_date'],
            'customer_message' => $post['customer_message'],
            'mobile_phone' => $post['mobile_phone'],
            'latitude' => $post['latitude'] ?? null,
            'longitude' => $post['longitude'] ?? null,
            'booking_status' => BookingStatus::WAITING_TO_CONFIRM->value,
            'customer_confirm_status' => ConfirmStatus::WAITING_TO_CONFIRM->value,
            'worker_confirm_status' => ConfirmStatus::WAITING_TO_CONFIRM->value,
            'author_id' => $user->id,
            'work_id' => $work->id,
        ]);

        // author_id is the recipient here, and notificationable points at what the
        // notification is about — the columns are named from the notification's side.
        UserNotification::create([
            'title' => 'มีการจองงานใหม่',
            'details' => [
                'work_id' => $work->id,
                'work_title' => $work->title,
                'booking_id' => $booking->id,
            ],
            'notification_type' => NotificationType::WORK_BOOKING->value,
            'is_read' => false,
            'author_id' => $work->author_id,
            'notificationable_type' => Work::class,
            'notificationable_id' => $work->id,
        ]);

        $booking->load(['work.province', 'work.workType', 'author']);

        // The old endpoint returned only {status:true} with no id, leaving the client
        // to hunt for the booking it had just made.
        return (new WorkBookingResource($booking))->response()->setStatusCode(201);
    }

    /**
     * The worker who owns the listing confirms they will do the job.
     */
    public function confirmByWorker(Request $request, WorkBooking $workBooking): WorkBookingResource
    {
        $this->assertOwner($request, $workBooking);

        $workBooking->worker_confirm_status = ConfirmStatus::CONFIRM->value;
        $this->syncBookingStatus($workBooking);

        return new WorkBookingResource($workBooking->fresh(['work.province', 'work.workType', 'author']));
    }

    /**
     * The customer who booked confirms the arrangement.
     */
    public function confirmByCustomer(Request $request, WorkBooking $workBooking): WorkBookingResource
    {
        $this->assertCustomer($request, $workBooking);

        $workBooking->customer_confirm_status = ConfirmStatus::CONFIRM->value;
        $this->syncBookingStatus($workBooking);

        return new WorkBookingResource($workBooking->fresh(['work.province', 'work.workType', 'author']));
    }

    /**
     * Either side may stand down before the job starts.
     */
    public function cancel(Request $request, WorkBooking $workBooking): WorkBookingResource
    {
        $this->assertParticipant($request, $workBooking);

        if ($workBooking->booking_status === BookingStatus::CLOSE->value) {
            throw new AccessDeniedHttpException('งานนี้ปิดการจองเรียบร้อยแล้ว');
        }

        $workBooking->booking_status = BookingStatus::CANCEL->value;
        $workBooking->save();

        return new WorkBookingResource($workBooking->fresh(['work.province', 'work.workType', 'author']));
    }

    /**
     * A booking is confirmed once both sides have confirmed.
     */
    private function syncBookingStatus(WorkBooking $booking): void
    {
        if ($booking->worker_confirm_status === ConfirmStatus::CONFIRM->value
            && $booking->customer_confirm_status === ConfirmStatus::CONFIRM->value) {
            $booking->booking_status = BookingStatus::CONFIRM->value;
        }

        $booking->save();
    }

    private function assertOwner(Request $request, WorkBooking $booking): void
    {
        if ($booking->work?->author_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์จัดการการจองนี้');
        }
    }

    private function assertCustomer(Request $request, WorkBooking $booking): void
    {
        if ($booking->author_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์จัดการการจองนี้');
        }
    }

    private function assertParticipant(Request $request, WorkBooking $booking): void
    {
        if ($booking->author_id === $request->user()->id) {
            return;
        }

        $this->assertOwner($request, $booking);
    }
}
