<?php

namespace App\Livewire\Backend\UserReport;

use App\Models\UserReport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; 

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deleteReport($id)
    {
        $report = UserReport::findOrFail($id);
        $report->delete();
        session()->flash('success', 'ลบรายงานเรียบร้อยแล้ว');
    }

    public function render()
    {
        $query = UserReport::with(['reporter', 'reportedUser', 'resolver'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('reporter', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('reportedUser', function($q3) {
                      $q3->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $reports = $query->paginate(10);

        return view('livewire.backend.user-report.index', compact('reports'));
    }
}
