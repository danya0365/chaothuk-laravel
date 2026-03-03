<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\RecruitBooking;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Calendar extends Component
{
    public int $year;
    public int $month;
    public string $monthLabel = '';
    public array $days = [];
    public array $bookedDates = [];
    public ?array $selectedDayBookings = null;
    public ?string $selectedDate = null;

    public function mount(): void
    {
        $this->year  = now()->year;
        $this->month = now()->month;
        $this->buildCalendar();
    }

    public function prevMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year  = $date->year;
        $this->month = $date->month;
        $this->selectedDayBookings = null;
        $this->selectedDate = null;
        $this->buildCalendar();
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year  = $date->year;
        $this->month = $date->month;
        $this->selectedDayBookings = null;
        $this->selectedDate = null;
        $this->buildCalendar();
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $uid = auth()->id();

        $workBookings = WorkBooking::with(['work', 'author'])
            ->where('booking_date', $date)
            ->where(function ($q) use ($uid) {
                $q->where('author_id', $uid)
                  ->orWhereHas('work', fn($wq) => $wq->where('author_id', $uid));
            })
            ->get()
            ->map(fn($b) => [
                'id'        => $b->id,
                'type'      => 'work',
                'title'     => $b->work?->title ?? '-',
                'status'    => $b->booking_status,
                'phone'     => $b->mobile_phone,
                'message'   => $b->customer_message,
                'author'    => $b->author?->name ?? '-',
                'date'      => $b->booking_date,
            ])
            ->toArray();

        $recruitBookings = RecruitBooking::with(['recruit', 'author'])
            ->where('booking_date', $date)
            ->where(function ($q) use ($uid) {
                $q->where('author_id', $uid)
                  ->orWhereHas('recruit', fn($rq) => $rq->where('author_id', $uid));
            })
            ->get()
            ->map(fn($b) => [
                'id'        => $b->id,
                'type'      => 'recruit',
                'title'     => $b->recruit?->title ?? '-',
                'status'    => $b->booking_status,
                'phone'     => $b->mobile_phone,
                'message'   => $b->customer_message,
                'author'    => $b->author?->name ?? '-',
                'date'      => $b->booking_date,
            ])
            ->toArray();

        $this->selectedDayBookings = array_merge($workBookings, $recruitBookings);
    }

    protected function buildCalendar(): void
    {
        $uid = auth()->id();
        $start = Carbon::create($this->year, $this->month, 1);
        $end   = $start->copy()->endOfMonth();

        $this->monthLabel = $start->locale('th')->translatedFormat('F Y');

        // Get booked dates for this month (both as customer and as work/recruit owner)
        $workDates = WorkBooking::whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->where(function ($q) use ($uid) {
                $q->where('author_id', $uid)
                  ->orWhereHas('work', fn($wq) => $wq->where('author_id', $uid));
            })
            ->pluck('booking_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $recruitDates = RecruitBooking::whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
            ->where(function ($q) use ($uid) {
                $q->where('author_id', $uid)
                  ->orWhereHas('recruit', fn($rq) => $rq->where('author_id', $uid));
            })
            ->pluck('booking_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $this->bookedDates = array_values(array_unique(array_merge($workDates, $recruitDates)));

        // Build calendar grid
        $firstDayOfWeek = $start->dayOfWeek; // 0=Sun
        $daysInMonth    = $start->daysInMonth;
        $this->days     = [];

        // Padding before first day
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $this->days[] = ['day' => null, 'date' => null];
        }

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $this->year, $this->month, $d);
            $this->days[] = [
                'day'    => $d,
                'date'   => $dateStr,
                'booked' => in_array($dateStr, $this->bookedDates),
                'today'  => $dateStr === now()->toDateString(),
            ];
        }
    }

    public function render()
    {
        return view('livewire.frontend.calendar')
            ->layout('frontend.layout', ['title' => 'ปฏิทินการจอง — Chaothuk']);
    }
}
