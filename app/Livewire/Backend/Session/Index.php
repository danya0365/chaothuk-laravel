<?php

namespace App\Livewire\Backend\Session;

use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('layouts.backend')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function deleteSession($id)
    {
        // Don't allow an admin to delete their own current session via this button
        // as it would log them out abruptly without warning.
        if ($id === session()->getId()) {
            session()->flash('error', 'ไม่สามารถเตะ (Kick) เซสชันปัจจุบันของคุณเองได้');
            return;
        }

        $session = Session::find($id);
        if ($session) {
            $session->delete();
            session()->flash('success', 'ลบเซสชันเรียบร้อยแล้ว ผู้ใช้จะต้องเข้าสู่ระบบใหม่');
        } else {
            session()->flash('error', 'ไม่พบเซสชันนี้ในระบบ');
        }
    }

    public function render()
    {
        $query = Session::with('user')
            ->whereNotNull('user_id'); // We primarily care about logged-in users

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('first_name', 'like', '%' . $this->search . '%')
                         ->orWhere('last_name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Order by most recent activity
        $query->orderBy('last_activity', 'desc');

        return view('livewire.backend.session.index', [
            'sessions' => $query->paginate(20),
            'title' => 'ระบบจัดการเซสชัน (Session Management)'
        ]);
    }
}
