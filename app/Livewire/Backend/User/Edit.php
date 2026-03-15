<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.backend')]
#[Title('แก้ไขข้อมูลสมาชิก - Admin')]
class Edit extends Component
{
    use WithFileUploads;

    public User $user;

    public $name = '';
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $mobile_phone = '';
    public $role_ids = [];
    public $permissions_data = [];
    public $password = ''; // Only if changing
    public $password_confirmation = '';

    #[Validate('nullable|image|max:2048')]
    public $new_profile_image;

    #[Validate('nullable|image|max:5120')]
    public $new_cover_image;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->mobile_phone = $user->mobile_phone;
        $this->role_ids = $user->roles->pluck('id')->toArray();
        foreach($user->permissions as $perm) {
            $this->permissions_data[$perm->id] = [
                'value' => $perm->pivot->data ? 'true' : 'false',
                'desc' => $perm->pivot->desc ?? ''
            ];
        }
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
            'permissions_data' => 'array',
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
        if ($this->new_profile_image) {
            // ลบไฟล์เดิม (เผื่อเป็น path เก่า)
            if ($this->user->profile_image) {
                $oldPath = \Illuminate\Support\Str::after($this->user->profile_image, '/storage/');
                Storage::disk('public')->delete($oldPath);
            }
            // ลบโฟลเดอร์ avatar เดิมทิ้งทั้งหมดเพื่อให้เหลือแค่รูปใหม่รูปล่าสุด
            Storage::disk('public')->deleteDirectory("users/{$this->user->id}/avatar");
            
            $path = $this->new_profile_image->store("users/{$this->user->id}/avatar", 'public');
            $data['profile_image'] = asset('storage/' . $path);
        }

        if ($this->new_cover_image) {
            // ลบไฟล์เดิม (เผื่อเป็น path เก่า)
            if ($this->user->cover_image) {
                $oldPath = \Illuminate\Support\Str::after($this->user->cover_image, '/storage/');
                Storage::disk('public')->delete($oldPath);
            }
            // ลบโฟลเดอร์ cover เดิมทิ้งทั้งหมด
            Storage::disk('public')->deleteDirectory("users/{$this->user->id}/cover");
            
            $path = $this->new_cover_image->store("users/{$this->user->id}/cover", 'public');
            $data['cover_image'] = asset('storage/' . $path);
        }

        $this->user->update($data);

        $this->user->syncUserRoles($this->role_ids ?? []);
        
        $userPermissions = [];
        if (!empty($this->permissions_data)) {
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
        }
        $this->user->syncUserPermissions($userPermissions);

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
