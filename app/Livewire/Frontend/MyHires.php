<?php

namespace App\Livewire\Frontend;

use App\Models\WorkBooking;
use App\Models\WorkSession;
use Livewire\Component;
use Livewire\WithPagination;

class MyHires extends Component
{
    use WithPagination;

    public string $tab = 'pending'; // pending, active, completed, cancelled

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = WorkBooking::where('author_id', auth()->id())->findOrFail($bookingId);
        $booking->update(['booking_status' => 'cancel']);
        session()->flash('success', 'ยกเลิกคำขอจองแล้ว');
    }

    public function render()
    {
        $uid = auth()->id();
        $paginatedData = null;

        if ($this->tab === 'pending') {
            // Find bookings made by this user that are not yet turned into sessions
            // author_id on WorkBooking is the person who clicks booking (Customer)
            $paginatedData = WorkBooking::with(['work.author', 'work.province'])
                ->where('author_id', $uid)
                ->whereIn('booking_status', ['waiting-to-confirm', 'confirm'])
                ->whereNotIn('id', function($q) {
                    $q->select('bookingable_id')->from('work_sessions')
                      ->where('bookingable_type', WorkBooking::class)
                      ->whereNull('deleted_at');
                })
                ->latest()
                ->paginate(10);
        } else {
            $statusMap = [
                'active' => ['active', 'paused'],
                'completed' => ['completed'],
                'cancelled' => ['cancelled']
            ];
            
            // For sessions, the buyer is the customer_id
            $paginatedData = WorkSession::with(['worker', 'sessionable'])
                ->where('customer_id', $uid)
                ->whereIn('status', $statusMap[$this->tab] ?? ['active'])
                ->latest('started_at')
                ->paginate(10);
        }

        // Stats
        $stats = [
            'pending'   => WorkBooking::where('author_id', $uid)
                            ->whereIn('booking_status', ['waiting-to-confirm', 'confirm'])
                            ->whereNotIn('id', function($q) {
                                $q->select('bookingable_id')->from('work_sessions')
                                  ->where('bookingable_type', WorkBooking::class)
                                  ->whereNull('deleted_at');
                            })->count(),
            'active'    => WorkSession::where('customer_id', $uid)->whereIn('status', ['active', 'paused'])->count(),
            'completed' => WorkSession::where('customer_id', $uid)->where('status', 'completed')->count(),
            'cancelled' => WorkSession::where('customer_id', $uid)->where('status', 'cancelled')->count(),
        ];

        return view('livewire.frontend.my-hires', [
            'items' => $paginatedData,
            'stats' => $stats,
        ])->layout('frontend.layout', ['title' => 'รายการว่าจ้าง — Chaothuk']);
    }
}
