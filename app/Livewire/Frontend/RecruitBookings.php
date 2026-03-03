<?php

namespace App\Livewire\Frontend;

use App\Models\Recruit;
use App\Models\RecruitBooking;
use Livewire\Component;
use Livewire\WithPagination;

class RecruitBookings extends Component
{
    use WithPagination;

    public int $id;
    public ?Recruit $recruit = null;
    public string $filterStatus = '';

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->recruit = Recruit::findOrFail($id);

        if (!auth()->check() || auth()->id() !== $this->recruit->author_id) {
            abort(403);
        }
    }

    public function confirmBooking(int $bookingId): void
    {
        $booking = RecruitBooking::where('recruit_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'confirm']);
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = RecruitBooking::where('recruit_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'cancel']);
    }

    public function closeBooking(int $bookingId): void
    {
        $booking = RecruitBooking::where('recruit_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'close']);
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = RecruitBooking::with('author')
            ->where('recruit_id', $this->id)
            ->latest();

        if ($this->filterStatus !== '') {
            $query->where('booking_status', $this->filterStatus);
        }

        $stats = [
            'total'   => RecruitBooking::where('recruit_id', $this->id)->count(),
            'waiting' => RecruitBooking::where('recruit_id', $this->id)->where('booking_status', 'waiting-to-confirm')->count(),
            'confirm' => RecruitBooking::where('recruit_id', $this->id)->where('booking_status', 'confirm')->count(),
            'close'   => RecruitBooking::where('recruit_id', $this->id)->where('booking_status', 'close')->count(),
            'cancel'  => RecruitBooking::where('recruit_id', $this->id)->where('booking_status', 'cancel')->count(),
        ];

        return view('livewire.frontend.recruit-bookings', [
            'bookings' => $query->paginate(20),
            'stats'    => $stats,
        ])->layout('frontend.layout', ['title' => 'รายการสมัคร — Chaothuk']);
    }
}
