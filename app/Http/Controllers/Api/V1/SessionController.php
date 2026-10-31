<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SessionCollection;
use App\Http\Resources\Api\V1\SessionLocationLogResource;
use App\Http\Resources\Api\V1\SessionResource;
use App\Models\SessionLocationLog;
use App\Models\WorkBooking;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Work sessions — the live record of a confirmed booking actually being done.
 *
 * Only the work side reaches the API, matching where the web starts sessions
 * (MyOrders / WorkDetail): the listing owner begins the session for one of their
 * confirmed bookings, and the two participants hold every later action. Everyone
 * else gets refused — the session id travels from the path, and ids are small
 * sequential integers.
 */
class SessionController extends Controller
{
    /**
     * Every session where the caller is the worker or the customer, newest first.
     *
     * `?status=` narrows to one phase (active / paused / completed / cancelled).
     */
    public function index(Request $request): SessionCollection
    {
        $userId = $request->user()->id;

        $query = WorkSession::query()
            ->with(['worker', 'customer', 'sessionable.province', 'sessionable.workType', 'latestLocation'])
            ->where(function ($query) use ($userId) {
                $query->where('worker_id', $userId)->orWhere('customer_id', $userId);
            });

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sessions = $query->latest('started_at')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50))
            ->withQueryString();

        return SessionCollection::make($sessions);
    }

    /**
     * Start a session for one of the caller's confirmed work bookings.
     *
     * Web flow mirrored: the listing owner chooses a confirmed booking and the
     * price defaults to the listing's. Running the same booking twice is refused
     * so a double tap never produces two live sessions.
     */
    public function start(Request $request, int $workBooking): SessionResource
    {
        $booking = WorkBooking::query()
            ->with('work')
            ->whereHas('work', fn ($query) => $query->where('author_id', $request->user()->id))
            ->find($workBooking);

        if (! $booking) {
            throw new AccessDeniedHttpException('ไม่พบการจองของประกาศที่คุณดูแล');
        }

        if ($booking->booking_status !== BookingStatus::CONFIRM->value) {
            throw new AccessDeniedHttpException('เริ่มเซสชันได้เฉพาะการจองที่ยืนยันแล้ว');
        }

        $running = WorkSession::query()
            ->where('bookingable_type', WorkBooking::class)
            ->where('bookingable_id', $booking->id)
            ->whereIn('status', ['active', 'paused'])
            ->exists();

        if ($running) {
            throw new AccessDeniedHttpException('การจองนี้มีเซสชันที่ยังทำงานอยู่แล้ว');
        }

        $validated = $request->validate([
            'price_agreed' => ['nullable', 'numeric', 'min:0'],
        ]);

        $session = WorkSession::create([
            'sessionable_type' => $booking->work::class,
            'sessionable_id' => $booking->work_id,
            'worker_id' => $request->user()->id,
            'customer_id' => $booking->author_id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id' => $booking->id,
            'started_at' => now(),
            'price_agreed' => $validated['price_agreed'] ?? $booking->work->price,
            'status' => 'active',
            'worker_confirm' => 'confirmed', // the worker proved intent by starting
        ]);

        $session->load(['worker', 'customer', 'sessionable.province', 'sessionable.workType', 'latestLocation']);

        return new SessionResource($session);
    }

    /**
     * One session, participant-eyes only, with the full location trail.
     */
    public function show(Request $request, int $session): SessionResource
    {
        $found = $this->callerSession($request, $session);

        $found->load([
            'worker',
            'customer',
            'sessionable.province',
            'sessionable.workType',
            'locationLogs' => fn ($query) => $query->orderBy('recorded_at')->limit(200),
        ]);

        return new SessionResource($found);
    }

    /**
     * Pause the clock — the web lets either side do it, so does the phone.
     */
    public function pause(Request $request, int $session): SessionResource
    {
        $found = $this->callerSession($request, $session);

        if ($found->status !== 'active') {
            throw new AccessDeniedHttpException('หยุดชั่วคราวได้เฉพาะเซสชันที่กำลังรันอยู่');
        }

        $found->update(['status' => 'paused']);

        return new SessionResource($this->refreshLoaded($found));
    }

    /**
     * Resume a paused session.
     */
    public function resume(Request $request, int $session): SessionResource
    {
        $found = $this->callerSession($request, $session);

        if ($found->status !== 'paused') {
            throw new AccessDeniedHttpException('เริ่มงานต่อได้เฉพาะเซสชันที่หยุดชั่วคราว');
        }

        $found->update(['status' => 'active']);

        return new SessionResource($this->refreshLoaded($found));
    }

    /**
     * Stop for good — the duration freezes and both confirms open.
     */
    public function stop(Request $request, int $session): SessionResource
    {
        $found = $this->callerSession($request, $session);

        if ($found->status !== 'active' && $found->status !== 'paused') {
            throw new AccessDeniedHttpException('เซสชันนี้จบไปแล้ว');
        }

        // Carbon 3 returns fractional minutes; the calendar rounds down so the
        // recorded time never overstates the work actually done.
        $duration = (int) floor($found->started_at->diffInMinutes(now()));

        $found->update([
            'status' => 'completed',
            'ended_at' => now(),
            'total_duration_minutes' => $duration,
        ]);

        return new SessionResource($this->refreshLoaded($found));
    }

    /**
     * The caller's half of the two-sided confirmation.
     */
    public function confirm(Request $request, int $session): SessionResource
    {
        $found = $this->callerSession($request, $session);

        $field = $found->worker_id === $request->user()->id ? 'worker_confirm' : 'customer_confirm';
        $found->update([$field => 'confirmed']);

        return new SessionResource($this->refreshLoaded($found));
    }

    /**
     * Drop one GPS ping. Active sessions only — a completed session has nothing
     * left to locate, so a straggling watcher is refused.
     */
    public function logLocation(Request $request, int $session): SessionLocationLogResource
    {
        $found = $this->callerSession($request, $session);

        if ($found->status !== 'active') {
            throw new AccessDeniedHttpException('บันทึกพิกัดได้เฉพาะเซสชันที่กำลังรันอยู่');
        }

        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
            'speed' => ['nullable', 'numeric', 'min:0'],
            'heading' => ['nullable', 'numeric', 'between:0,360'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $log = SessionLocationLog::create([
            'session_id' => $found->id,
            'user_id' => $request->user()->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'accuracy' => $validated['accuracy'] ?? null,
            'speed' => $validated['speed'] ?? null,
            'heading' => $validated['heading'] ?? null,
            'recorded_at' => $validated['recorded_at'] ?? now(),
        ]);

        return new SessionLocationLogResource($log);
    }

    /**
     * The session record — only if the caller is one of its two participants.
     */
    private function callerSession(Request $request, int $session): WorkSession
    {
        $userId = $request->user()->id;

        $found = WorkSession::query()
            ->where(function ($query) use ($userId) {
                $query->where('worker_id', $userId)->orWhere('customer_id', $userId);
            })
            ->find($session);

        if (! $found) {
            throw new AccessDeniedHttpException('ไม่พบเซสชันนี้');
        }

        return $found;
    }

    /**
     * Fresh state with the same relations the list/detail flows promise.
     */
    private function refreshLoaded(WorkSession $session): WorkSession
    {
        $session->refresh();

        $session->load(['worker', 'customer', 'sessionable.province', 'sessionable.workType', 'latestLocation']);

        return $session;
    }
}
