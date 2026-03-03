<?php

namespace App\Livewire\Frontend;

use App\Models\SessionLocationLog;
use App\Models\WorkSession;
use Livewire\Component;

class SessionDetail extends Component
{
    public int $id;
    public ?WorkSession $session = null;
    public bool $isWorker = false;
    public bool $isCustomer = false;
    public array $locationLogs = [];
    public ?string $flashMessage = null;

    public function mount(int $id): void
    {
        $uid = auth()->id();
        $this->id = $id;
        $this->session = WorkSession::with(['worker', 'customer'])
            ->where(function ($q) use ($uid) {
                $q->where('worker_id', $uid)->orWhere('customer_id', $uid);
            })
            ->findOrFail($id);

        $this->isWorker = $this->session->worker_id === $uid;
        $this->isCustomer = $this->session->customer_id === $uid;
        $this->loadLocationLogs();
    }

    public function stopSession(): void
    {
        if ($this->session->status !== 'active' && $this->session->status !== 'paused') return;

        $started = $this->session->started_at;
        $now = now();
        $duration = $started->diffInMinutes($now);

        $this->session->update([
            'status'                 => 'completed',
            'ended_at'               => $now,
            'total_duration_minutes' => $duration,
        ]);

        $this->session->refresh();
        $this->flashMessage = '✅ หยุดงานเรียบร้อย — ระยะเวลา ' . floor($duration / 60) . ' ชม. ' . ($duration % 60) . ' น.';
    }

    public function pauseSession(): void
    {
        if ($this->session->status !== 'active') return;
        $this->session->update(['status' => 'paused']);
        $this->session->refresh();
        $this->flashMessage = '⏸ หยุดชั่วคราว';
    }

    public function resumeSession(): void
    {
        if ($this->session->status !== 'paused') return;
        $this->session->update(['status' => 'active']);
        $this->session->refresh();
        $this->flashMessage = '▶️ เริ่มงานต่อ';
    }

    public function confirmSession(): void
    {
        $uid = auth()->id();
        $field = $this->isWorker ? 'worker_confirm' : 'customer_confirm';
        $this->session->update([$field => 'confirmed']);
        $this->session->refresh();
        $this->flashMessage = '✅ ยืนยันเซสชันเรียบร้อย';
    }

    /**
     * Called from browser JavaScript via Livewire to log GPS coordinate.
     */
    public function logLocation(float $lat, float $lng, ?float $accuracy = null, ?float $speed = null, ?float $heading = null): void
    {
        if ($this->session->status !== 'active') return;

        SessionLocationLog::create([
            'session_id'  => $this->id,
            'user_id'     => auth()->id(),
            'latitude'    => $lat,
            'longitude'   => $lng,
            'accuracy'    => $accuracy,
            'speed'       => $speed,
            'heading'     => $heading,
            'recorded_at' => now(),
        ]);

        $this->loadLocationLogs();
    }

    public function refreshLocations(): void
    {
        $this->loadLocationLogs();
    }

    protected function loadLocationLogs(): void
    {
        $this->locationLogs = SessionLocationLog::where('session_id', $this->id)
            ->orderBy('recorded_at')
            ->get()
            ->map(fn($l) => [
                'lat'         => (float) $l->latitude,
                'lng'         => (float) $l->longitude,
                'accuracy'    => $l->accuracy,
                'speed'       => $l->speed,
                'recorded_at' => $l->recorded_at?->format('H:i:s'),
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.session-detail')
            ->layout('frontend.layout', ['title' => 'เซสชัน #' . $this->id . ' — Chaothuk']);
    }
}
