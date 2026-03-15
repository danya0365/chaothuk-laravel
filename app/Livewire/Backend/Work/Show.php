<?php

namespace App\Livewire\Backend\Work;

use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkReview;
use App\Models\UserReputation;
use App\Models\WorkAvailability;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('รายละเอียดงานเช่า - Admin')]
class Show extends Component
{
    public Work $work;
    public $providerStats = [];
    public $bookedDates = [];
    public $availabilities = [];
    public $recentBookings = [];
    public $recentReviews = [];

    public function mount(Work $work)
    {
        // Require extra relationships for a complete view
        $this->work = $work->load(['author', 'categories', 'province', 'workType']);

        // 1. Compute Provider Stats (Exactly Like Frontend)
        $authorId = $this->work->author_id;

        // Total works by this provider
        $totalWorks = \App\Models\Work::where('author_id', $authorId)->count();
        $workIds = \App\Models\Work::where('author_id', $authorId)->pluck('id');
        
        $completedJobs = WorkBooking::whereIn('work_id', $workIds)
            ->whereIn('booking_status', ['confirm', 'close'])->count();
        $totalBookings = WorkBooking::whereIn('work_id', $workIds)->count();

        // Reputation
        $rep = UserReputation::where('user_id', $authorId)->first();

        $this->providerStats = [
            'total_works'     => $totalWorks,
            'completed_jobs'  => $completedJobs,
            'total_bookings'  => $totalBookings,
            'completion_rate' => $totalBookings > 0 ? round(($completedJobs / $totalBookings) * 100) : 0,
            'trust_level'     => $rep?->trust_level ?? 'none',
            'trust_label'     => $rep?->trust_level_label ?? 'ผู้ใช้ทั่วไป',
            'overall_score'   => $rep?->overall_score ? round($rep->overall_score, 1) : 0,
        ];

        // 2. Fetch Booked Dates (gauge busyness, exactly like Frontend)
        $this->bookedDates = WorkBooking::where('work_id', $this->work->id)
            ->whereIn('booking_status', ['waiting-to-confirm', 'confirm'])
            ->whereNotNull('booking_date')
            ->pluck('booking_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        // 3. Availabilities Schedule (Exactly like frontend)
        $this->availabilities = WorkAvailability::where('work_id', $this->work->id)
            ->where('is_available', true)
            ->orderBy('day_of_week')
            ->get()
            ->groupBy('day_of_week')
            ->map(fn ($slots) => $slots->map(fn ($s) => substr($s->start_time, 0, 5) . '-' . substr($s->end_time, 0, 5))->implode(', '))
            ->toArray();

        // 4. Recent Bookings & Reviews
        $this->recentBookings = $this->work->bookings()->with('author')->latest()->take(10)->get();
        $this->recentReviews = $this->work->reviews()->with('author')->latest()->take(10)->get();
    }

    public function toggleFeature()
    {
        $this->work->display_priority = $this->work->display_priority > 0 ? 0 : 100;
        $this->work->save();

        $action = $this->work->display_priority > 0 ? 'แนะนำ' : 'เลิกแนะนำ';
        session()->flash('success', "ตั้งค่าให้งาน {$this->work->code} เป็นรายการ{$action} แล้ว");
    }

    public function toggleSuspend()
    {
        $this->work->is_suspended = !$this->work->is_suspended;
        $this->work->save();

        $action = $this->work->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} งานรหัส {$this->work->code} สำเร็จ");
    }

    public function render()
    {
        return view('livewire.backend.work.show');
    }
}
