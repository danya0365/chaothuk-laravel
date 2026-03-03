<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\WorkAvailability;
use App\Models\WorkBlockedDate;
use Livewire\Component;

class WorkAvailabilityManage extends Component
{
    public int $id;
    public ?Work $work = null;
    public array $schedule = [];
    public array $blockedDates = [];
    public ?string $flashMessage = null;

    // Form for adding blocked date
    public string $newBlockedDate = '';
    public string $newBlockedReason = '';

    private array $dayNames = [
        0 => 'อาทิตย์', 1 => 'จันทร์', 2 => 'อังคาร', 3 => 'พุธ',
        4 => 'พฤหัสบดี', 5 => 'ศุกร์', 6 => 'เสาร์',
    ];

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->work = Work::where('author_id', auth()->id())->findOrFail($id);
        $this->loadSchedule();
        $this->loadBlockedDates();
    }

    public function toggleDay(int $day): void
    {
        $existing = WorkAvailability::where('work_id', $this->id)
            ->where('day_of_week', $day)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            WorkAvailability::create([
                'work_id'      => $this->id,
                'day_of_week'  => $day,
                'start_time'   => '08:00',
                'end_time'     => '17:00',
                'is_available' => true,
            ]);
        }

        $this->loadSchedule();
    }

    public function updateTime(int $day, string $field, string $value): void
    {
        WorkAvailability::where('work_id', $this->id)
            ->where('day_of_week', $day)
            ->update([$field => $value]);

        $this->loadSchedule();
    }

    public function addBlockedDate(): void
    {
        $this->validate([
            'newBlockedDate' => 'required|date|after_or_equal:today',
        ]);

        WorkBlockedDate::firstOrCreate(
            ['work_id' => $this->id, 'blocked_date' => $this->newBlockedDate],
            ['reason' => $this->newBlockedReason ?: null]
        );

        $this->newBlockedDate = '';
        $this->newBlockedReason = '';
        $this->flashMessage = '✅ เพิ่มวันหยุดเรียบร้อย';
        $this->loadBlockedDates();
    }

    public function removeBlockedDate(int $id): void
    {
        WorkBlockedDate::where('id', $id)->where('work_id', $this->id)->delete();
        $this->loadBlockedDates();
    }

    protected function loadSchedule(): void
    {
        $avails = WorkAvailability::where('work_id', $this->id)->get()->keyBy('day_of_week');

        $this->schedule = [];
        for ($d = 0; $d <= 6; $d++) {
            $a = $avails->get($d);
            $this->schedule[] = [
                'day'        => $d,
                'name'       => $this->dayNames[$d],
                'is_active'  => $a !== null,
                'start_time' => $a?->start_time ? substr($a->start_time, 0, 5) : '08:00',
                'end_time'   => $a?->end_time ? substr($a->end_time, 0, 5) : '17:00',
            ];
        }
    }

    protected function loadBlockedDates(): void
    {
        $this->blockedDates = WorkBlockedDate::where('work_id', $this->id)
            ->where('blocked_date', '>=', now()->toDateString())
            ->orderBy('blocked_date')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.work-availability-manage')
            ->layout('frontend.layout', ['title' => 'ตารางว่าง — Chaothuk']);
    }
}
