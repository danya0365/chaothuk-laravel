<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserNotificationCollection;
use App\Http\Resources\Api\V1\UserNotificationResource;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Notifications addressed to the signed-in user.
 *
 * Writing already happens wherever something happens — a booking creates one for
 * the listing owner in WorkBookingController — so this controller only reads what
 * belongs to the caller and lets them clear it. Every query is scoped to author_id
 * because the id travels in from the path.
 */
class NotificationController extends Controller
{
    /**
     * The caller's notifications, newest first.
     */
    public function index(Request $request): UserNotificationCollection
    {
        $notifications = UserNotification::query()
            ->where('author_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(min(max($request->integer('per_page', 20), 1), 50))
            ->withQueryString();

        return UserNotificationCollection::make($notifications);
    }

    /**
     * How many the caller has not read, for the tab badge.
     *
     * Separate from index so the app can poll this cheaply instead of refetching
     * the whole list on every focus.
     */
    public function unreadCount(Request $request): \Illuminate\Http\JsonResponse
    {
        $count = UserNotification::query()
            ->where('author_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['data' => ['unread_count' => $count]]);
    }

    /**
     * Mark one notification as read.
     *
     * The update is scoped to author_id rather than looked up first, so a guessed
     * id from another user simply matches nothing instead of leaking.
     */
    public function markRead(Request $request, int $notification): UserNotificationResource
    {
        $record = UserNotification::query()
            ->where('author_id', $request->user()->id)
            ->find($notification);

        if (! $record) {
            throw new AccessDeniedHttpException('ไม่พบการแจ้งเตือนนี้');
        }

        $record->update(['is_read' => true]);

        return new UserNotificationResource($record);
    }

    /**
     * Clear every unread notification for the caller.
     */
    public function markAllRead(Request $request): \Illuminate\Http\JsonResponse
    {
        $updated = UserNotification::query()
            ->where('author_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['data' => ['updated' => $updated]]);
    }
}
