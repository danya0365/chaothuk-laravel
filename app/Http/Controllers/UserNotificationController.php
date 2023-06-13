<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

/**
 * Class UserNotificationController
 * @package App\Http\Controllers
 */
class UserNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userNotifications = UserNotification::paginate();

        return view('user-notification.index', compact('userNotifications'))
            ->with('i', (request()->input('page', 1) - 1) * $userNotifications->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userNotification = new UserNotification();
        return view('user-notification.create', compact('userNotification'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(UserNotification::$rules);

        $userNotification = UserNotification::create($request->all());

        return redirect()->route('user-notifications.index')
            ->with('success', 'UserNotification created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userNotification = UserNotification::find($id);

        return view('user-notification.show', compact('userNotification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userNotification = UserNotification::find($id);

        return view('user-notification.edit', compact('userNotification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  UserNotification $userNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserNotification $userNotification)
    {
        request()->validate(UserNotification::$rules);

        $userNotification->update($request->all());

        return redirect()->route('user-notifications.index')
            ->with('success', 'UserNotification updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userNotification = UserNotification::find($id)->delete();

        return redirect()->route('user-notifications.index')
            ->with('success', 'UserNotification deleted successfully');
    }
}
