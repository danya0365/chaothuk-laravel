<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\WorkBooking;
use Livewire\Component;
use Livewire\WithPagination;

class WorkBookings extends Component
{
    use WithPagination;

    public int $id;
    public ?Work $work = null;
    public string $filterStatus = '';

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->work = Work::findOrFail($id);

        if (!auth()->check() || auth()->id() !== $this->work->author_id) {
            abort(403);
        }
    }

    public function confirmBooking(int $bookingId): void
    {
        $booking = WorkBooking::where('work_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'confirm', 'worker_confirm_status' => 'confirm']);
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = WorkBooking::where('work_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'cancel']);
    }

    public function closeBooking(int $bookingId): void
    {
        $booking = WorkBooking::where('work_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'close']);
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = WorkBooking::with('author')
            ->where('work_id', $this->id)
            ->latest();

        if ($this->filterStatus !== '') {
            $query->where('booking_status', $this->filterStatus);
        }

        $stats = [
            'total'   => WorkBooking::where('work_id', $this->id)->count(),
            'waiting' => WorkBooking::where('work_id', $this->id)->where('booking_status', 'waiting-to-confirm')->count(),
            'confirm' => WorkBooking::where('work_id', $this->id)->where('booking_status', 'confirm')->count(),
            'close'   => WorkBooking::where('work_id', $this->id)->where('booking_status', 'close')->count(),
            'cancel'  => WorkBooking::where('work_id', $this->id)->where('booking_status', 'cancel')->count(),
        ];

        return view('livewire.frontend.work-bookings', [
            'bookings' => $query->paginate(20),
            'stats'    => $stats,
        ])->layout('frontend.layout', ['title' => 'รายการจอง — Chaothuk']);
    }
}
