<?php

namespace App\Livewire\Frontend;

use App\Models\RecruitBooking;
use App\Models\WorkBooking;
use Livewire\Component;

class MyBookings extends Component
{
    public string $tab = 'work';
    public ?array $workBookings = null;
    public ?array $recruitBookings = null;
    public string $statusFilter = 'all';
    public ?string $flashMessage = null;

    public function mount(): void
    {
        $this->loadBookings();
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->loadBookings();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->loadBookings();
    }

    public function cancelBooking(string $type, int $id): void
    {
        $uid = auth()->id();

        if ($type === 'work') {
            WorkBooking::where('id', $id)
                ->where('author_id', $uid)
                ->where('booking_status', 'waiting-to-confirm')
                ->update(['booking_status' => 'cancel']);
        } else {
            RecruitBooking::where('id', $id)
                ->where('author_id', $uid)
                ->where('booking_status', 'waiting-to-confirm')
                ->update(['booking_status' => 'cancel']);
        }

        $this->flashMessage = '✅ ยกเลิกการจองเรียบร้อย';
        $this->loadBookings();
    }

    protected function loadBookings(): void
    {
        $uid = auth()->id();
        $status = $this->statusFilter;

        $workQuery = WorkBooking::with(['work.author', 'work.province'])
            ->where('author_id', $uid);
        if ($status !== 'all') {
            $workQuery->where('booking_status', $status);
        }
        $this->workBookings = $workQuery->latest()->limit(30)->get()->toArray();

        $recruitQuery = RecruitBooking::with(['recruit.author', 'recruit.province'])
            ->where('author_id', $uid);
        if ($status !== 'all') {
            $recruitQuery->where('booking_status', $status);
        }
        $this->recruitBookings = $recruitQuery->latest()->limit(30)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.my-bookings')
            ->layout('frontend.layout', ['title' => 'การจองของฉัน — Chaothuk']);
    }
}
