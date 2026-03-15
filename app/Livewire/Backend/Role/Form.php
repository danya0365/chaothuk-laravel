<?php

namespace App\Livewire\Backend\Role;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการบทบาทระบบ - Admin')]
class Form extends Component
{
    public ?Role $role = null;

    public $name = '';
    public $slug = '';
    public $desc = '';
    
    // Array to hold selected permission IDs
    public $permission_ids = [];

    public function mount(Role $role = null)
    {
        if ($role && $role->exists) {
            $this->role = $role;
            $this->name = $role->name;
            $this->slug = $role->slug;
            $this->desc = $role->desc;
            
            // Load current permission IDs pivot
            $this->permission_ids = $role->permissions()->pluck('permissions.id')->toArray();
        }
    }

    public function rules()
    {
        $id = $this->role ? $this->role->id : null;
        return [
            'name' => 'required|min:2',
            'slug' => ['required', 'alpha_dash', Rule::unique('roles', 'slug')->ignore($id)],
            'desc' => 'nullable|string',
            'permission_ids' => 'array',
            'permission_ids.*' => 'exists:permissions,id',
        ];
    }

    public function updatedName($value)
    {
        if (!$this->role) {
            // Auto-generate slug for new records from english text or leave for manual input
            $this->slug = \Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => strtolower($this->slug),
            'desc' => $this->desc,
        ];

        if ($this->role && $this->role->exists) {
            $this->role->update($data);
            $this->role->permissions()->sync($this->permission_ids);
            
            session()->flash('success', 'อัปเดตบทบาทสำเร็จ');
        } else {
            $newRole = Role::create($data);
            $newRole->permissions()->sync($this->permission_ids);
            
            session()->flash('success', 'สร้างบทบาทสำเร็จ');
        }

        return $this->redirectRoute('backend.roles.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.role.form', [
            'available_permissions' => Permission::all()
        ]);
    }
}
