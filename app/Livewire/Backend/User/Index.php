<?php

namespace App\Livewire\Backend\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการสมาชิก - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
            return;
        }

        // Prevent deleting root user
        if ($user->email === config('auth.supervisor.email')) {
            session()->flash('error', 'ไม่สามารถลบบัญชีผู้ดูแลระบบสูงสุดได้');
            return;
        }

        $user->delete();
        session()->flash('success', 'ลบผู้ใช้งานสำเร็จแล้ว');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('mobile_phone', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.backend.user.index', [
            'users' => $users
        ]);
    }
}
