<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use App\Http\Requests\UserActivityLogRequest;

/**
 * Class UserActivityLogController
 * @package App\Http\Controllers
 */
class UserActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userActivityLogs = UserActivityLog::orderBy('id', 'desc')->paginate();

        return view('user-activity-log.index', compact('userActivityLogs'))
            ->with('i', (request()->input('page', 1) - 1) * $userActivityLogs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userActivityLog = new UserActivityLog();
        return view('user-activity-log.create', compact('userActivityLog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserActivityLogRequest $request)
    {
        UserActivityLog::create($request->validated());

        return redirect()->route('user-activity-logs.index')
            ->with('success', 'UserActivityLog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userActivityLog = UserActivityLog::find($id);

        return view('user-activity-log.show', compact('userActivityLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userActivityLog = UserActivityLog::find($id);

        return view('user-activity-log.edit', compact('userActivityLog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserActivityLogRequest $request, UserActivityLog $userActivityLog)
    {
        $userActivityLog->update($request->validated());

        return redirect()->route('user-activity-logs.index')
            ->with('success', 'UserActivityLog updated successfully');
    }

    public function destroy($id)
    {
        UserActivityLog::find($id)->delete();

        return redirect()->route('user-activity-logs.index')
            ->with('success', 'UserActivityLog deleted successfully');
    }
}
