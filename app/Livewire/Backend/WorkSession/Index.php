<?php

namespace App\Livewire\Backend\WorkSession;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WorkSession;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = WorkSession::with(['worker', 'customer', 'sessionable']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('worker', function ($u) {
                      $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('customer', function ($u) {
                      $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $sessions = $query->latest('id')->paginate(20);
        $statuses = WorkSession::select('status')->distinct()->pluck('status');

        return view('livewire.backend.work-session.index', [
            'sessions' => $sessions,
            'statuses' => $statuses
        ])->layout('layouts.backend');
    }
}
