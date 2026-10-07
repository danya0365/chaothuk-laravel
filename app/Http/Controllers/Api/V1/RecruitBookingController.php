<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BookingStatus;
use App\Enums\ConfirmStatus;
use App\Enums\NotificationType;
use App\Enums\RecruitStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Resources\Api\V1\RecruitBookingCollection;
use App\Http\Resources\Api\V1\RecruitBookingResource;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\UserNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Bookings of recruits — a worker applying for a job someone posted.
 *
 * The sides are mirrored relative to works: author_id is the applicant, while the
 * recruit's author is the employer.
 */
class RecruitBookingController extends Controller
{
    public function indexAsApplicant(Request $request): RecruitBookingCollection
    {
        $bookings = RecruitBooking::query()
            ->with(['recruit.province', 'recruit.workType', 'author'])
            ->whereBelongsTo($request->user(), 'author')
            ->orderByDesc('created_at')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50))
            ->withQueryString();

        return RecruitBookingCollection::make($bookings);
    }

    public function indexAsEmployer(Request $request): RecruitBookingCollection
    {
        $bookings = RecruitBooking::query()
            ->with(['recruit.province', 'recruit.workType', 'author'])
            ->whereHas('recruit', fn ($query) => $query->whereBelongsTo($request->user(), 'author'))
            ->orderByDesc('created_at')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50))
            ->withQueryString();

        return RecruitBookingCollection::make($bookings);
    }

    public function show(Request $request, RecruitBooking $recruitBooking): RecruitBookingResource
    {
        $this->assertParticipant($request, $recruitBooking);

        return new RecruitBookingResource($recruitBooking->load(['recruit.province', 'recruit.workType', 'author']));
    }

    /**
     * Apply for a recruit.
     *
     * @throws AccessDeniedHttpException when applying to your own posting
     */
    public function store(StoreBookingRequest $request, Recruit $recruit): JsonResponse
    {
        $user = $request->user();

        if ($recruit->author_id === $user->id) {
            throw new AccessDeniedHttpException('ไม่สามารถสมัครงานของตัวเองได้');
        }

        if ($recruit->recruit_status !== RecruitStatus::STAND_BY->value) {
            throw new AccessDeniedHttpException('ประกาศนี้ปิดรับสมัครแล้ว');
        }

        $post = $request->validated();

        $booking = RecruitBooking::create([
            'booking_date' => $post['booking_date'],
            'customer_message' => $post['customer_message'],
            'mobile_phone' => $post['mobile_phone'],
            'booking_status' => BookingStatus::WAITING_TO_CONFIRM->value,
            'customer_confirm_status' => ConfirmStatus::WAITING_TO_CONFIRM->value,
            'worker_confirm_status' => ConfirmStatus::WAITING_TO_CONFIRM->value,
            'author_id' => $user->id,
            'recruit_id' => $recruit->id,
        ]);

        UserNotification::create([
            'title' => 'มีผู้สมัครงานใหม่',
            'details' => [
                'recruit_id' => $recruit->id,
                'recruit_title' => $recruit->title,
                'booking_id' => $booking->id,
            ],
            'notification_type' => NotificationType::RECRUIT_BOOKING->value,
            'is_read' => false,
            'author_id' => $recruit->author_id,
            'notificationable_type' => Recruit::class,
            'notificationable_id' => $recruit->id,
        ]);

        $booking->load(['recruit.province', 'recruit.workType', 'author']);

        return (new RecruitBookingResource($booking))->response()->setStatusCode(201);
    }

    /**
     * The employer who posted the recruit picks the applicant.
     */
    public function confirmByEmployer(Request $request, RecruitBooking $recruitBooking): RecruitBookingResource
    {
        $this->assertEmployer($request, $recruitBooking);

        $recruitBooking->worker_confirm_status = ConfirmStatus::CONFIRM->value;
        $this->syncBookingStatus($recruitBooking);

        return new RecruitBookingResource($recruitBooking->fresh(['recruit.province', 'recruit.workType', 'author']));
    }

    /**
     * The applicant confirms the arrangement.
     */
    public function confirmByApplicant(Request $request, RecruitBooking $recruitBooking): RecruitBookingResource
    {
        $this->assertApplicant($request, $recruitBooking);

        $recruitBooking->customer_confirm_status = ConfirmStatus::CONFIRM->value;
        $this->syncBookingStatus($recruitBooking);

        return new RecruitBookingResource($recruitBooking->fresh(['recruit.province', 'recruit.workType', 'author']));
    }

    public function cancel(Request $request, RecruitBooking $recruitBooking): RecruitBookingResource
    {
        $this->assertParticipant($request, $recruitBooking);

        if ($recruitBooking->booking_status === BookingStatus::CLOSE->value) {
            throw new AccessDeniedHttpException('การสมัครนี้ปิดเรียบร้อยแล้ว');
        }

        $recruitBooking->booking_status = BookingStatus::CANCEL->value;
        $recruitBooking->save();

        return new RecruitBookingResource($recruitBooking->fresh(['recruit.province', 'recruit.workType', 'author']));
    }

    private function syncBookingStatus(RecruitBooking $booking): void
    {
        if ($booking->worker_confirm_status === ConfirmStatus::CONFIRM->value
            && $booking->customer_confirm_status === ConfirmStatus::CONFIRM->value) {
            $booking->booking_status = BookingStatus::CONFIRM->value;
        }

        $booking->save();
    }

    private function assertEmployer(Request $request, RecruitBooking $booking): void
    {
        if ($booking->recruit?->author_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์จัดการการสมัครนี้');
        }
    }

    private function assertApplicant(Request $request, RecruitBooking $booking): void
    {
        if ($booking->author_id !== $request->user()->id) {
            throw new AccessDeniedHttpException('คุณไม่มีสิทธิ์จัดการการสมัครนี้');
        }
    }

    private function assertParticipant(Request $request, RecruitBooking $booking): void
    {
        if ($booking->author_id === $request->user()->id) {
            return;
        }

        $this->assertEmployer($request, $booking);
    }
}
