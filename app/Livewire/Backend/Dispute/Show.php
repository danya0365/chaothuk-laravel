<?php

namespace App\Livewire\Backend\Dispute;

use App\Models\Dispute;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.backend')]
class Show extends Component
{
    public Dispute $dispute;
    public $resolution = '';
    public $status = '';

    public function mount(Dispute $dispute)
    {
        $this->dispute = $dispute->load(['reporter', 'respondent', 'admin', 'bookingable']);
        $this->resolution = $dispute->resolution;
        $this->status = $dispute->status;
    }

    public function resolveDispute()
    {
        $this->validate([
            'resolution' => 'required|string|max:2000',
            'status' => 'required|in:resolved,closed',
        ], [
            'resolution.required' => 'กรุณาระบุบทสรุป/การตัดสินก่อนเปลี่ยนสถานะ',
            'status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        $this->dispute->update([
            'status' => $this->status,
            'resolution' => $this->resolution,
            'admin_id' => Auth::id(),
        ]);

        $this->dispute->load('admin');
        
        $msg = $this->status === 'resolved' ? 'บันทึกการไกล่เกลี่ยสำเร็จ' : 'ปิดข้อพิพาท/ยุติเรื่องสำเร็จ';
        session()->flash('success', $msg);
    }

    public function reopenDispute()
    {
        $this->dispute->update([
            'status' => 'open',
            'admin_id' => null,
            // We intentionally keep the old resolution text for history, 
            // but the admin can edit it when re-resolving.
        ]);

        $this->status = 'open';
        $this->dispute->load('admin');
        
        session()->flash('success', 'เปิดข้อพิพาทกลับมาเพื่อพิจารณาใหม่อีกครั้ง');
    }

    public function render()
    {
        return view('livewire.backend.dispute.show');
    }
}
