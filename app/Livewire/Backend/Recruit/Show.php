<?php

namespace App\Livewire\Backend\Recruit;

use App\Models\Recruit;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('รายละเอียดประกาศจ้างงาน - Admin')]
class Show extends Component
{
    public Recruit $recruit;
    public $recentBookings = [];
    public $recentReviews = [];

    public function mount(Recruit $recruit)
    {
        $this->recruit = $recruit->load(['author', 'province', 'workType', 'categories']);

        // Recent Bookings & Reviews
        $this->recentBookings = $this->recruit->bookings()->with('author')->latest()->take(10)->get();
        $this->recentReviews = $this->recruit->reviews()->with('author')->latest()->take(10)->get();
    }

    public function toggleFeature()
    {
        $this->recruit->display_priority = $this->recruit->display_priority > 0 ? 0 : 100;
        $this->recruit->save();

        $action = $this->recruit->display_priority > 0 ? 'แนะนำ' : 'เลิกแนะนำ';
        session()->flash('success', "ตั้งค่าให้ประกาศนี้เป็นรายการ{$action} แล้ว");
    }

    public function toggleSuspend()
    {
        $this->recruit->is_suspended = !$this->recruit->is_suspended;
        $this->recruit->save();

        $action = $this->recruit->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} สำเร็จ");
    }

    public function render()
    {
        return view('livewire.backend.recruit.show');
    }
}
