<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserBackendRequest;
use App\Models\User;
use App\Models\UserBackend;
use App\Traits\SelectOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserBackendController extends Controller
{
    use SelectOption;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query()->whereIn('role_id', [Role::BACKEND->value, Role::SUPERVISOR->value])->paginate()->withQueryString();

        return view('backend.user-backend.index', compact('users'))
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
        $genderSelections = $this->gender();
        $yesNoSelections = $this->yesNo();
        return view('backend.user-backend.create', compact('user', 'genderSelections', 'yesNoSelections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserBackendRequest $request)
    {
        if (!Auth::user()->role?->isSupervisor()) {
            return redirect()->route('backend.backends.index')
                ->with('error', 'User was not create.');
        }

        $request->validated();
        $post = $request->all();

        $post['person_info'] = $post;
        $post['password'] = Hash::make($post['password']);
        $post['role_id'] = Role::BACKEND->value;
        $user = User::create($post);
        $user->backend()->create($post);
        return redirect()->route('backend.user-backends.index')
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
        return view('backend.user-backend.show', compact('user'));
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
        $yesNoSelections = $this->yesNo();
        return view('backend.user-backend.edit', compact('user', 'yesNoSelections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserBackendRequest $request, UserBackend $userBackend)
    {
        if (!Auth::user()->role?->isSupervisor()) {
            return redirect()->route('backend.backends.index')
                ->with('error', 'User was not update.');
        }

        $request->validated();
        $post = $request->all();

        $userId = $post['user_id'];
        $user = User::query()->find($userId);
        if (trim($post['password'])) {
            $post['password'] = Hash::make(trim($post['password']));
        } else {
            unset($post['password']);
        }
        $user->update($post);

        $userBackend = UserBackend::query()->find($userId);
        if (!$userBackend) {
            $userBackend = UserBackend::create(['user_id' => $userId, ...$post]);
        } else {
            $userBackend->update($post);
        }

        return redirect()->route('backend.user-backends.index')
            ->with('success', 'User updated successfully');
    }
}
