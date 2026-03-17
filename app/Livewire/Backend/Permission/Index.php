<?php

namespace App\Livewire\Backend\Permission;

use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการสิทธิเข้าถึง (Permissions) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function mount()
    {
        if (!auth()->user()->isPermission(\App\Enums\Permission::MANAGE_PERMISSION->value)) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deletePermission($id)
    {
        if (!auth()->user()->isPermission(\App\Enums\Permission::MANAGE_PERMISSION->value)) {
            abort(403, 'Unauthorized action.');
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();
        
        session()->flash('success', 'ลบสิทธิเข้าถึงสำเร็จแล้ว');
    }

    public function render()
    {
        $permissions = Permission::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.backend.permission.index', [
            'permissions' => $permissions
        ]);
    }
}
