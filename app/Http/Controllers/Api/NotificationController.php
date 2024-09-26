<?php

namespace App\Http\Controllers\Api;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

/**
 * Class NotificationController
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $notifications = Notification::query()->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new NotificationCollection($notifications),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->total_reads += 1;
            $notification->save();
        }

        return response()->json([
            'status' => $notification ? true : false,
            'data' => new NotificationResource($notification),
        ], 200);
    }

    /**
     * Display lastUpdate.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastUpdate()
    {
        $notification = Notification::orderBy('updated_at', 'desc')->take(1)->first();

        return response()->json([
            'status' => $notification ? true : false,
            'data' => $notification ? new NotificationResource($notification) : null,
        ], 200);
    }
}
