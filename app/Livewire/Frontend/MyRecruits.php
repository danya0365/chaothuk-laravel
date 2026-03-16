<?php

namespace App\Livewire\Frontend;

use App\Models\Recruit;
use Livewire\Component;
use Livewire\WithPagination;

class MyRecruits extends Component
{
    use WithPagination;

    public function deleteRecruit(int $recruitId): void
    {
        if (!auth()->check()) return;
        $recruit = Recruit::where('id', $recruitId)->where('author_id', auth()->id())->first();
        if ($recruit) {
            $recruit->delete();
            session()->flash('success', 'ลบประกาศรับสมัครงานเรียบร้อยแล้ว');
        }
    }

    public function render()
    {
        $recruits = Recruit::with(['province', 'workType'])
            ->where('author_id', auth()->id())
            ->latest()
            ->paginate(12);

        $stats = [
            'total' => Recruit::where('author_id', auth()->id())->count(),
        ];

        return view('livewire.frontend.my-recruits', compact('recruits', 'stats'))
            ->layout('frontend.layout', ['title' => 'จัดการประกาศงานของฉัน — Chaothuk']);
    }
}
