<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

#[Layout('layouts.backend')]
#[Title('เพิ่มสมาชิกใหม่ - Admin')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('nullable|image|max:2048')]
    public $profile_image;

    #[Validate('nullable|image|max:5120')]
    public $cover_image;

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

    public $permissions_data = [];

    #[Validate('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile_phone' => $this->mobile_phone,
            'password' => Hash::make($this->password),
        ];

        $user = User::create($data);

        $updates = [];
        if ($this->profile_image) {
            $path = $this->profile_image->store("users/{$user->id}/avatar", 'public');
            $updates['profile_image'] = asset('storage/' . $path);
        }

        if ($this->cover_image) {
            $path = $this->cover_image->store("users/{$user->id}/cover", 'public');
            $updates['cover_image'] = asset('storage/' . $path);
        }

        if (!empty($updates)) {
            $user->update($updates);
        }

        if (!empty($this->role_ids)) {
            $user->roles()->sync($this->role_ids);
        }

        if (!empty($this->role_ids)) {
            $user->syncUserRoles($this->role_ids);
        }

        if (!empty($this->permissions_data)) {
            $userPermissions = [];
            foreach($this->permissions_data as $permId => $valueData) {
                // Determine truth value logic
                $boolValue = null;
                if (is_array($valueData) && isset($valueData['value'])) {
                    $boolValue = $valueData['value'];
                } elseif (is_string($valueData)) {
                    $boolValue = $valueData;
                }

                if($boolValue === 'true' || $boolValue === 'false') {
                    $desc = is_array($valueData) && isset($valueData['desc']) ? $valueData['desc'] : '';
                    $userPermissions[] = [
                        'permission_id' => $permId,
                        'data' => $boolValue === 'true' ? 1 : 0,
                        'desc' => $desc
                    ];
                }
            }
            $user->syncUserPermissions($userPermissions);
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
