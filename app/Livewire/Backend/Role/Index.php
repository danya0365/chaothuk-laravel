<?php

namespace App\Livewire\Backend\Role;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการบทบาท (Roles) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting critical roles (Super Admin)
        if ($role->slug === 'super-admin') {
            session()->flash('error', 'ไม่สามารถลบบทบาทนี้ได้ เนื่องจากเป็นบทบาทหลักของระบบ');
            return;
        }

        $role->delete();
        session()->flash('success', 'ลบบทบาทสำเร็จแล้ว');
    }

    public function render()
    {
        $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        return view('livewire.backend.role.index', [
            'roles' => $roles
        ]);
    }
}
