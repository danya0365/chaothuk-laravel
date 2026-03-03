<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SessionLocationLog;
use App\Models\WorkSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * GET /api/sessions — list my sessions
     */
    public function index(Request $request): JsonResponse
    {
        $uid = $request->user()->id;

        $sessions = WorkSession::with(['worker:id,name,profile_image', 'customer:id,name,profile_image'])
            ->where(fn($q) => $q->where('worker_id', $uid)->orWhere('customer_id', $uid))
            ->latest('started_at')
            ->limit(50)
            ->get();

        return response()->json(['data' => $sessions]);
    }

    /**
     * GET /api/sessions/{id} — session detail
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $uid = $request->user()->id;

        $session = WorkSession::with(['worker:id,name,profile_image', 'customer:id,name,profile_image'])
            ->where(fn($q) => $q->where('worker_id', $uid)->orWhere('customer_id', $uid))
            ->findOrFail($id);

        return response()->json(['data' => $session]);
    }

    /**
     * POST /api/sessions/{id}/location — log GPS coordinate
     */
    public function logLocation(Request $request, int $id): JsonResponse
    {
        $uid = $request->user()->id;

        $session = WorkSession::where(fn($q) => $q->where('worker_id', $uid)->orWhere('customer_id', $uid))
            ->where('status', 'active')
            ->findOrFail($id);

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy'  => 'nullable|numeric|min:0',
            'speed'     => 'nullable|numeric|min:0',
            'heading'   => 'nullable|numeric|between:0,360',
        ]);

        $log = SessionLocationLog::create([
            'session_id'  => $session->id,
            'user_id'     => $uid,
            'latitude'    => $data['latitude'],
            'longitude'   => $data['longitude'],
            'accuracy'    => $data['accuracy'] ?? null,
            'speed'       => $data['speed'] ?? null,
            'heading'     => $data['heading'] ?? null,
            'recorded_at' => now(),
        ]);

        return response()->json(['data' => $log, 'message' => 'Location logged'], 201);
    }

    /**
     * GET /api/sessions/{id}/locations — get all location logs
     */
    public function getLocations(Request $request, int $id): JsonResponse
    {
        $uid = $request->user()->id;

        $session = WorkSession::where(fn($q) => $q->where('worker_id', $uid)->orWhere('customer_id', $uid))
            ->findOrFail($id);

        $logs = SessionLocationLog::where('session_id', $session->id)
            ->orderBy('recorded_at')
            ->get();

        return response()->json(['data' => $logs]);
    }

    /**
     * POST /api/sessions/{id}/stop — stop session
     */
    public function stop(Request $request, int $id): JsonResponse
    {
        $uid = $request->user()->id;

        $session = WorkSession::where(fn($q) => $q->where('worker_id', $uid)->orWhere('customer_id', $uid))
            ->whereIn('status', ['active', 'paused'])
            ->findOrFail($id);

        $duration = $session->started_at->diffInMinutes(now());
        $session->update([
            'status'                 => 'completed',
            'ended_at'               => now(),
            'total_duration_minutes' => $duration,
        ]);

        return response()->json(['data' => $session->fresh(), 'message' => 'Session stopped']);
    }

    /**
     * POST /api/sessions/{id}/confirm — confirm session (worker or customer)
     */
    public function confirm(Request $request, int $id): JsonResponse
    {
        $uid = $request->user()->id;

        $session = WorkSession::where('status', 'completed')
            ->where(fn($q) => $q->where('worker_id', $uid)->orWhere('customer_id', $uid))
            ->findOrFail($id);

        $field = $session->worker_id === $uid ? 'worker_confirm' : 'customer_confirm';
        $session->update([$field => 'confirmed']);

        return response()->json(['data' => $session->fresh(), 'message' => 'Confirmed']);
    }

    /**
     * POST /api/sessions/start — start a new session
     */
    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sessionable_type' => 'required|in:work,recruit',
            'sessionable_id'   => 'required|integer',
            'customer_id'      => 'required|exists:users,id',
            'price_agreed'     => 'nullable|numeric|min:0',
            'booking_id'       => 'nullable|integer',
            'booking_type'     => 'nullable|in:work_booking,recruit_booking',
        ]);

        $uid = $request->user()->id;

        $typeMap = [
            'work'    => \App\Models\Work::class,
            'recruit' => \App\Models\Recruit::class,
        ];
        $bookingTypeMap = [
            'work_booking'    => \App\Models\WorkBooking::class,
            'recruit_booking' => \App\Models\RecruitBooking::class,
        ];

        $session = WorkSession::create([
            'sessionable_type' => $typeMap[$data['sessionable_type']],
            'sessionable_id'   => $data['sessionable_id'],
            'worker_id'        => $uid,
            'customer_id'      => $data['customer_id'],
            'bookingable_type' => isset($data['booking_type']) ? ($bookingTypeMap[$data['booking_type']] ?? null) : null,
            'bookingable_id'   => $data['booking_id'] ?? null,
            'started_at'       => now(),
            'price_agreed'     => $data['price_agreed'] ?? null,
            'status'           => 'active',
            'worker_confirm'   => 'confirmed',
        ]);

        return response()->json(['data' => $session, 'message' => 'Session started'], 201);
    }
}
