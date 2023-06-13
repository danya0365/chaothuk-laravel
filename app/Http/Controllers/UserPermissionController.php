<?php

namespace App\Http\Controllers;

use App\Models\UserPermission;
use Illuminate\Http\Request;

/**
 * Class UserPermissionController
 * @package App\Http\Controllers
 */
class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userPermissions = UserPermission::paginate();

        return view('user-permission.index', compact('userPermissions'))
            ->with('i', (request()->input('page', 1) - 1) * $userPermissions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userPermission = new UserPermission();
        return view('user-permission.create', compact('userPermission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(UserPermission::$rules);

        $userPermission = UserPermission::create($request->all());

        return redirect()->route('user-permissions.index')
            ->with('success', 'UserPermission created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userPermission = UserPermission::find($id);

        return view('user-permission.show', compact('userPermission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userPermission = UserPermission::find($id);

        return view('user-permission.edit', compact('userPermission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  UserPermission $userPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPermission $userPermission)
    {
        request()->validate(UserPermission::$rules);

        $userPermission->update($request->all());

        return redirect()->route('user-permissions.index')
            ->with('success', 'UserPermission updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userPermission = UserPermission::find($id)->delete();

        return redirect()->route('user-permissions.index')
            ->with('success', 'UserPermission deleted successfully');
    }
}
