<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Traits\SelectOption;

class RoleController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::orderBy('id', 'asc')->paginate();

        return view('backend.role.index', compact('roles'))
            ->with('i', (request()->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role();
        $permissionSelections = $this->permission();
        return view('backend.role.create', compact('role', 'permissionSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        /** @var \App\Models/User $user */
        $user = auth()->user();
        if (!$user->isCanManageRole()) {
            return redirect()->route('backend.roles.index')
                ->with('error', 'Role was not create.');
        }
        $request->validated();
        $post = $request->all();
        $role = Role::create($post);
        $role->syncRolePermissions($post['role_permissions']);
        return redirect()->route('backend.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        return view('backend.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissionSelections = $this->permission();
        return view('backend.role.edit', compact('role', 'permissionSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        /** @var \App\Models/User $user */
        $user = auth()->user();
        if (!$user->isCanManageRole()) {
            return redirect()->route('backend.roles.index')
                ->with('error', 'Role was not update.');
        }
        $request->validated();
        $post = $request->all();
        $role->update($post);
        $role->syncRolePermissions($post['role_permissions']);
        return redirect()->route('backend.roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        /** @var \App\Models/User $user */
        $user = auth()->user();
        if (!$user->isCanManageRole()) {
            return redirect()->route('backend.roles.index')
                ->with('error', 'Role was not update.');
        }

        $role = Role::find($id);

        if (!$role) {
            return redirect()->route('backend.roles.index')
                ->with('error', 'Role not found');
        }
        $role->delete();
        return redirect()->route('backend.roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
