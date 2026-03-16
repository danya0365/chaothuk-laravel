<?php

namespace App\Livewire\Backend\WorkSession;

use Livewire\Component;
use App\Models\WorkSession;

class Show extends Component
{
    public WorkSession $session;

    public function mount(WorkSession $session)
    {
        $this->session = $session->load(['worker', 'customer', 'sessionable', 'bookingable', 'locationLogs' => function($q) {
            $q->latest();
        }]);
    }

    public function render()
    {
        return view('livewire.backend.work-session.show')->layout('layouts.backend');
    }
}
