<?php

namespace App\Livewire\Backend\ActivityLog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserActivityLog;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = UserActivityLog::with('user');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('activity_type', 'like', '%' . $this->search . '%')
                  ->orWhere('activity_value', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($u) {
                      $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->typeFilter) {
            $query->where('activity_type', $this->typeFilter);
        }

        $logs = $query->latest()->paginate(30);

        // Get unique activity types for the filter dropdown
        $activityTypes = UserActivityLog::select('activity_type')->distinct()->pluck('activity_type');

        return view('livewire.backend.activity-log.index', [
            'logs' => $logs,
            'activityTypes' => $activityTypes
        ])->layout('layouts.backend');
    }
}
