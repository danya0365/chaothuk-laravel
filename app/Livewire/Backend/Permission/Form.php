<?php

namespace App\Livewire\Backend\Permission;

use App\Models\Permission;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการสิทธิเข้าถึง - Admin')]
class Form extends Component
{
    public ?Permission $permission = null;

    public $name = '';
    public $slug = '';
    public $desc = '';

    public function mount(Permission $permission = null)
    {
        if ($permission && $permission->exists) {
            $this->permission = $permission;
            $this->name = $permission->name;
            $this->slug = $permission->slug;
            $this->desc = $permission->desc;
        }
    }

    public function rules()
    {
        $id = $this->permission ? $this->permission->id : null;
        return [
            'name' => 'required|min:2',
            'slug' => ['required', 'alpha_dash', Rule::unique('permissions', 'slug')->ignore($id)],
            'desc' => 'nullable|string',
        ];
    }

    public function updatedName($value)
    {
        if (!$this->permission) {
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

        if ($this->permission && $this->permission->exists) {
            $this->permission->update($data);
            session()->flash('success', 'อัปเดตสิทธิเข้าถึงสำเร็จ');
        } else {
            Permission::create($data);
            session()->flash('success', 'สร้างสิทธิเข้าถึงสำเร็จ');
        }

        return $this->redirectRoute('backend.permissions.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.permission.form');
    }
}
