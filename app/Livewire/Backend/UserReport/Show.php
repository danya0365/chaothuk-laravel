<?php

namespace App\Livewire\Backend\UserReport;

use App\Models\UserReport;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.backend')]
class Show extends Component
{
    public UserReport $report;
    public $admin_note = '';

    public function mount(UserReport $report)
    {
        $this->report = $report->load(['reporter', 'reportedUser', 'resolver']);
        $this->admin_note = $report->admin_note;
    }

    public function resolveReport()
    {
        $this->validate([
            'admin_note' => 'required|string|max:1000',
        ], [
            'admin_note.required' => 'กรุณาระบุบันทึกการดำเนินการของแอดมินก่อนเปลี่ยนสถานะ',
        ]);

        $this->report->update([
            'status' => 'resolved',
            'admin_note' => $this->admin_note,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        $this->report->load('resolver');
        
        session()->flash('success', 'บันทึกการดำเนินการและปิดงานสำเร็จ');
    }

    public function reopenReport()
    {
        $this->report->update([
            'status' => 'pending',
            'resolved_by' => null,
            'resolved_at' => null,
        ]);

        $this->report->load('resolver');
        
        session()->flash('success', 'เปิดงานกลับมารอดำเนินการอีกครั้ง');
    }

    public function render()
    {
        return view('livewire.backend.user-report.show');
    }
}
