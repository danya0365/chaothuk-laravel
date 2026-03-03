<?php

namespace App\Livewire\Frontend;

use App\Models\WorkSession;
use Livewire\Component;

class MySessions extends Component
{
    public string $statusFilter = 'all';
    public array $sessions = [];

    public function mount(): void
    {
        $this->loadSessions();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->loadSessions();
    }

    protected function loadSessions(): void
    {
        $uid = auth()->id();

        $query = WorkSession::with(['worker', 'customer'])
            ->where(function ($q) use ($uid) {
                $q->where('worker_id', $uid)->orWhere('customer_id', $uid);
            });

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $this->sessions = $query->latest('started_at')
            ->limit(50)
            ->get()
            ->map(function ($s) use ($uid) {
                return [
                    'id'              => $s->id,
                    'sessionable_type' => class_basename($s->sessionable_type),
                    'sessionable_id'  => $s->sessionable_id,
                    'worker_name'     => $s->worker?->name ?? '-',
                    'customer_name'   => $s->customer?->name ?? '-',
                    'is_worker'       => $s->worker_id === $uid,
                    'started_at'      => $s->started_at?->format('d/m/Y H:i'),
                    'ended_at'        => $s->ended_at?->format('d/m/Y H:i'),
                    'duration'        => $s->total_duration_minutes,
                    'price_agreed'    => $s->price_agreed,
                    'status'          => $s->status,
                    'worker_confirm'  => $s->worker_confirm,
                    'customer_confirm' => $s->customer_confirm,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.my-sessions')
            ->layout('frontend.layout', ['title' => 'ประวัติการทำงาน — Chaothuk']);
    }
}
