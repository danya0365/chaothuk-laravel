<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Layout('layouts.backend')]
#[Title('เพิ่มสมาชิกใหม่ - Admin')]
class Create extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('nullable|string')]
    public $first_name = '';

    #[Validate('nullable|string')]
    public $last_name = '';

    #[Validate('required|email|unique:users,email')]
    public $email = '';

    #[Validate('nullable|string')]
    public $mobile_phone = '';

    #[Validate('array')]
    public $role_ids = [];

    #[Validate('array')]
    public $permission_ids = [];

    #[Validate('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_phone' => $this->mobile_phone,
            'password' => Hash::make($this->password),
        ]);

        if (!empty($this->role_ids)) {
            $user->roles()->sync($this->role_ids);
        }

        if (!empty($this->permission_ids)) {
            $user->permissions()->sync($this->permission_ids);
        }

        session()->flash('success', 'สร้างบัญชีผู้ใช้สำเร็จ');
        return $this->redirectRoute('backend.users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.user.create', [
            'roles' => \App\Models\Role::all(),
            'permissions' => \App\Models\Permission::all()
        ]);
    }
}
