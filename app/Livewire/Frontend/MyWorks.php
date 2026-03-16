<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\WorkBooking;
use Livewire\Component;
use Livewire\WithPagination;

class MyWorks extends Component
{
    use WithPagination;

    public function deleteWork(int $workId): void
    {
        if (!auth()->check()) return;
        $work = Work::where('id', $workId)->where('author_id', auth()->id())->first();
        if ($work) {
            // Check if there are active bookings before deleting
            $hasActiveBookings = WorkBooking::where('work_id', $workId)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();
                
            if ($hasActiveBookings) {
                session()->flash('error', 'ไม่สามารถลบงานนี้ได้เนื่องจากยังมีรายการจองที่รอการดำเนินการ');
                return;
            }
            
            $work->delete();
            session()->flash('success', 'ลบงานสำเร็จ');
        }
    }

    public function render()
    {
        $works = Work::with(['province', 'workType', 'activeFeature'])
            ->where('author_id', auth()->id())
            ->latest()
            ->paginate(12);

        $stats = [
            'total' => Work::where('author_id', auth()->id())->count(),
            'active_promotions' => Work::where('author_id', auth()->id())
                ->whereHas('activeFeature')
                ->count(),
            'total_likes' => Work::where('author_id', auth()->id())->sum('like_count'),
        ];

        return view('livewire.frontend.my-works', compact('works', 'stats'))
            ->layout('frontend.layout', ['title' => 'จัดการงานของฉัน — Chaothuk']);
    }
}
