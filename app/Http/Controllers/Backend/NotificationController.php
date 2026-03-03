<?php

namespace App\Http\Controllers\Backend;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
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
    public function index()
    {
        $notifications = Notification::orderBy('id', 'desc')->paginate();

        return view('backend.notification.index', compact('notifications'))
            ->with('i', (request()->input('page', 1) - 1) * $notifications->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notification = new Notification();
        $notificationTypes = NotificationType::values();
        $notificationTypeSelections = [];
        foreach ($notificationTypes as $key => $notificationType) {
            $notificationTypeSelections[] = [
                'id' => $key,
                'label' => __('notification.' . $notificationType),
                'value' => $notificationType
            ];
        }
        return view('backend.notification.create', compact('notification', 'notificationTypeSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Notification::$rules);

        $notification = Notification::create($request->all());

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification created successfully.');
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

        return view('backend.notification.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = Notification::find($id);
        $notificationTypes = NotificationType::values();
        $notificationTypeSelections = [];
        foreach ($notificationTypes as $key => $notificationType) {
            $notificationTypeSelections[] = [
                'id' => $key,
                'label' => __('notification.' . $notificationType),
                'value' => $notificationType
            ];
        }
        return view('backend.notification.edit', compact('notification', 'notificationTypeSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Notification $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        request()->validate(Notification::$rules);

        $notification->update($request->all());

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $notification = Notification::find($id)->delete();

        return redirect()->route('backend.notifications.index')
            ->with('success', 'Notification deleted successfully');
    }
}
