<?php

namespace App\Livewire\Frontend;

use App\Models\Recruit;
use App\Models\RecruitBooking;
use Livewire\Component;

class RecruitDetail extends Component
{
    public int $id;
    public ?Recruit $recruit = null;
    public bool $isOwner = false;
    public int $bookingsCount = 0;

    // Apply (booking) form
    public bool $showApplyForm = false;
    public string $applyPhone = '';
    public string $applyDate = '';
    public string $applyMessage = '';
    public ?string $applySuccess = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->recruit = Recruit::with(['author', 'province', 'workType', 'categories'])->findOrFail($id);
        $this->isOwner = auth()->check() && auth()->id() === $this->recruit->author_id;
        $this->bookingsCount = RecruitBooking::where('recruit_id', $this->id)->count();
    }

    public function submitApply(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }
        $this->validate(['applyPhone' => 'required|min:9', 'applyDate' => 'required|date']);

        RecruitBooking::create([
            'author_id'        => auth()->id(),
            'recruit_id'       => $this->id,
            'mobile_phone'     => $this->applyPhone,
            'booking_date'     => $this->applyDate,
            'customer_message' => $this->applyMessage,
            'booking_status'   => 'waiting-to-confirm',
        ]);

        $this->applySuccess = '✅ ส่งใบสมัครแล้ว เราจะติดต่อกลับเร็วๆ นี้';
        $this->applyPhone = $this->applyDate = $this->applyMessage = '';
        $this->showApplyForm = false;
    }

    public function render()
    {
        return view('livewire.frontend.recruit-detail')
            ->layout('frontend.layout', ['title' => ($this->recruit->title ?? 'Recruit') . ' — Chaothuk']);
    }
}
