<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('แก้ไขข้อมูลสมาชิก - Admin')]
class Edit extends Component
{
    public User $user;

    public $name = '';
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $mobile_phone = '';
    public $role_ids = [];
    public $permission_ids = [];
    public $password = ''; // Only if changing
    public $password_confirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->mobile_phone = $user->mobile_phone;
        $this->role_ids = $user->roles->pluck('id')->toArray();
        $this->permission_ids = $user->permissions->pluck('id')->toArray();
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'mobile_phone' => 'nullable|string',
            'role_ids' => 'array',
            'permission_ids' => 'array',
            'password' => 'nullable|min:8|confirmed',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_phone' => $this->mobile_phone,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        $this->user->roles()->sync($this->role_ids ?? []);
        $this->user->permissions()->sync($this->permission_ids ?? []);

        session()->flash('success', 'อัปเดตข้อมูลผู้ใช้สำเร็จ');
        return $this->redirectRoute('backend.users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.user.edit', [
            'roles' => \App\Models\Role::all(),
            'permissions' => \App\Models\Permission::all()
        ]);
    }
}
