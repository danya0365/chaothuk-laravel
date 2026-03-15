<?php

namespace App\Livewire\Backend\Log;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CronLog;

class CronIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = CronLog::query()
            ->when($this->search, function ($query) {
                // Since cron_type is stored, we might search by it, but mostly this is for generic filtering
                $query->where('cron_type', 'like', '%' . $this->search . '%')
                      ->orWhere('number_issue_point_executes', 'like', '%' . $this->search . '%')
                      ->orWhereDate('created_at', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.backend.log.cron-index', compact('logs'))
            ->layout('layouts.backend', ['title' => 'ประวัติการทำงานเบื้องหลัง (Cron Logs)']);
    }
}
