<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkLike;
use App\Models\UserReputation;
use Livewire\Component;

class WorkDetail extends Component
{
    public int $id;
    public ?Work $work = null;
    public bool $isOwner = false;
    public bool $showBookings = false;
    public array $bookings = [];
    public array $bookedDates = [];

    // Provider reputation summary
    public ?array $providerStats = null;

    // Booking form
    public bool $showBookingForm = false;
    public string $bookingMessage = '';
    public string $bookingPhone = '';
    public string $bookingDate = '';
    public ?string $bookingMessage2 = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->work = Work::with(['author', 'province', 'workType', 'categories'])->findOrFail($id);
        $this->isOwner = auth()->check() && auth()->id() === $this->work->author_id;
        $this->loadBookedDates();
        $this->loadProviderStats();
        if ($this->isOwner) {
            $this->loadBookings();
        }
    }

    public function loadProviderStats(): void
    {
        $authorId = $this->work->author_id;

        // Total works by this provider
        $totalWorks = Work::where('author_id', $authorId)->count();
        $workIds = Work::where('author_id', $authorId)->pluck('id');
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
            'trust_level'     => $rep?->trust_level ?? null,
            'trust_label'     => $rep?->trust_level_label ?? null,
            'overall_score'   => $rep?->overall_score ? round($rep->overall_score, 1) : null,
        ];
    }

    public function loadBookedDates(): void
    {
        $this->bookedDates = WorkBooking::where('work_id', $this->id)
            ->whereIn('booking_status', ['waiting-to-confirm', 'confirm'])
            ->whereNotNull('booking_date')
            ->pluck('booking_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();
    }

    public function loadBookings(): void
    {
        $this->bookings = WorkBooking::with('author')
            ->where('work_id', $this->id)
            ->latest()
            ->get()
            ->map(fn($b) => [
                'id'              => $b->id,
                'author_name'     => $b->author?->name ?? 'ผู้ใช้',
                'author_avatar'   => $b->author?->getAvatar(48) ?? '',
                'phone'           => $b->mobile_phone,
                'message'         => $b->customer_message,
                'date'            => $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '-',
                'status'          => $b->booking_status,
                'created_at'      => $b->created_at?->diffForHumans(),
            ])
            ->toArray();
    }

    public function confirmBooking(int $bookingId): void
    {
        if (!$this->isOwner) return;
        $booking = WorkBooking::where('work_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'confirm', 'worker_confirm_status' => 'confirm']);
        $this->loadBookings();
    }

    public function cancelBooking(int $bookingId): void
    {
        if (!$this->isOwner) return;
        $booking = WorkBooking::where('work_id', $this->id)->findOrFail($bookingId);
        $booking->update(['booking_status' => 'cancel']);
        $this->loadBookings();
    }

    public function toggleLike(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }
        $existing = WorkLike::where('author_id', auth()->id())->where('work_id', $this->id)->first();
        if ($existing) {
            $existing->delete();
            $this->work->decrement('like_count');
        } else {
            WorkLike::create(['author_id' => auth()->id(), 'work_id' => $this->id]);
            $this->work->increment('like_count');
        }
        $this->work->refresh();
    }

    public function submitBooking(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }

        $this->validate([
            'bookingPhone'   => 'required|min:9',
            'bookingDate'    => 'required|date',
            'bookingMessage' => 'nullable|string',
        ]);

        WorkBooking::create([
            'author_id'        => auth()->id(),
            'work_id'          => $this->id,
            'mobile_phone'     => $this->bookingPhone,
            'booking_date'     => $this->bookingDate,
            'customer_message' => $this->bookingMessage,
            'booking_status'   => 'waiting-to-confirm',
        ]);

        $this->bookingMessage2 = '✅ ส่งคำขอจองแล้ว เราจะติดต่อกลับเร็วๆ นี้';
        $this->bookingPhone = '';
        $this->bookingDate = '';
        $this->bookingMessage = '';
        $this->showBookingForm = false;
        $this->loadBookedDates();
    }

    public function render()
    {
        $isLiked = auth()->check()
            ? WorkLike::where('author_id', auth()->id())->where('work_id', $this->id)->exists()
            : false;

        return view('livewire.frontend.work-detail', compact('isLiked'))
            ->layout('frontend.layout', ['title' => ($this->work->title ?? 'Work') . ' — Chaothuk']);
    }
}
