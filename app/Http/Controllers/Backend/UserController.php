<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\SelectOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate();

        return view('backend.user.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $roleSelections = $this->role();
        $permissionSelections = $this->permission();
        return view('backend.user.create', compact('user', 'roleSelections', 'permissionSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $request->validated();
        $post = $request->all();
        $post['password'] = Hash::make($post['password']);
        $user = User::create($post);

        if (auth()->user()->isCanManagePermission()) {
            $user->syncUserPermissions($post['user_permissions']);
        }

        if (auth()->user()->isCanManageRole()) {
            $user->syncUserRoles($post['users_roles'] ?? []);
        }

        return redirect()->route('backend.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('backend.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roleSelections = $this->role();
        $permissionSelections = $this->permission();
        return view('backend.user.edit', compact('user', 'roleSelections', 'permissionSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $request->validated();
        $post = $request->all();

        if (trim($post['password'])) {
            $post['password'] = Hash::make(trim($post['password']));
        } else {
            unset($post['password']);
        }
        $user->update($post);
        if (auth()->user()->isCanManagePermission()) {
            $user->syncUserPermissions($post['user_permissions']);
        }
        if (auth()->user()->isCanManageRole()) {
            $user->syncUserRoles($post['users_roles'] ?? []);
        }
        return redirect()->route('backend.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('backend.users.index')
                ->with('error', 'User not found');
        }
        if ($user->email === config('auth.supervisor.email')) {
            return redirect()->route('backend.users.index')
                ->with('error', 'Supervisor cannot be delete');
        }
        $user->delete();
        return redirect()->route('backend.users.index')
            ->with('success', 'User deleted successfully');
    }
}