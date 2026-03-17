<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkSession;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    public string $tab = 'pending'; // pending, active, completed, cancelled

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function confirmBooking(int $bookingId): void
    {
        $booking = WorkBooking::whereHas('work', function($q) {
            $q->where('author_id', auth()->id());
        })->findOrFail($bookingId);
        
        $booking->update(['booking_status' => 'confirm', 'worker_confirm_status' => 'confirm']);
        session()->flash('success', 'รับออเดอร์เรียบร้อยแล้ว กรุณาเริ่มงานเมื่อพร้อม');
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = WorkBooking::whereHas('work', function($q) {
            $q->where('author_id', auth()->id());
        })->findOrFail($bookingId);
        
        $booking->update(['booking_status' => 'cancel']);
        session()->flash('success', 'ปฏิเสธ/ยกเลิกออเดอร์แล้ว');
    }

    public function startSession(int $bookingId, float $sessionPrice = null): void
    {
        $booking = WorkBooking::whereHas('work', function($q) {
            $q->where('author_id', auth()->id());
        })->where('booking_status', 'confirm')->findOrFail($bookingId);

        $session = WorkSession::create([
            'sessionable_type' => Work::class,
            'sessionable_id'   => $booking->work_id,
            'worker_id'        => auth()->id(),
            'customer_id'      => $booking->author_id,
            'bookingable_type' => WorkBooking::class,
            'bookingable_id'   => $booking->id,
            'started_at'       => now(),
            'price_agreed'     => $sessionPrice ?? $booking->work->price,
            'status'           => 'active',
            'worker_confirm'   => 'confirmed', // Seller already confirmed by starting
        ]);

        $this->redirect(route('frontend.sessions.show', $session->id), navigate: true);
    }

    public function render()
    {
        $uid = auth()->id();
        
        $pendingBookings = collect();
        $sessions = collect();
        $paginatedData = null;

        // Base Work ID scope for the seller
        $myWorkIds = Work::where('author_id', $uid)->pluck('id');

        if ($this->tab === 'pending') {
            $paginatedData = WorkBooking::with(['work', 'author'])
                ->whereIn('work_id', $myWorkIds)
                ->whereIn('booking_status', ['waiting-to-confirm', 'confirm'])
                // Exclude bookings that already have an active/completed session
                ->whereDoesntHave('notifications') // we will need to ensure sessions relationship or we just show them if they don't have session
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
            
            $paginatedData = WorkSession::with(['customer', 'sessionable'])
                ->where('worker_id', $uid)
                ->whereIn('status', $statusMap[$this->tab] ?? ['active'])
                ->latest('started_at')
                ->paginate(10);
                
            // Also append cancelled bookings into the 'cancelled' tab if needed (as a union or separate list).
            // For simplicity, we just show canceled sessions in 'cancelled', but we can merge them later.
        }

        // Stats
        $stats = [
            'pending'   => WorkBooking::whereIn('work_id', $myWorkIds)
                            ->whereIn('booking_status', ['waiting-to-confirm', 'confirm'])
                            ->whereNotIn('id', function($q) {
                                $q->select('bookingable_id')->from('work_sessions')
                                  ->where('bookingable_type', WorkBooking::class)
                                  ->whereNull('deleted_at');
                            })->count(),
            'active'    => WorkSession::where('worker_id', $uid)->whereIn('status', ['active', 'paused'])->count(),
            'completed' => WorkSession::where('worker_id', $uid)->where('status', 'completed')->count(),
            'cancelled' => WorkSession::where('worker_id', $uid)->where('status', 'cancelled')->count(),
        ];

        return view('livewire.frontend.my-orders', [
            'items' => $paginatedData,
            'stats' => $stats,
        ])->layout('frontend.layout', ['title' => 'ออเดอร์ลูกค้า — Chaothuk']);
    }
}
