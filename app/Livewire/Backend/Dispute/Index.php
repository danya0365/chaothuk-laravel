<?php

namespace App\Livewire\Backend\Dispute;

use App\Models\Dispute;
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

    public function deleteDispute($id)
    {
        $dispute = Dispute::findOrFail($id);
        $dispute->delete();
        session()->flash('success', 'ลบข้อพิพาทเรียบร้อยแล้ว');
    }

    public function render()
    {
        $query = Dispute::with(['reporter', 'respondent', 'admin'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('reason', 'like', '%' . $this->search . '%')
                  ->orWhereHas('reporter', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('respondent', function($q3) {
                      $q3->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $disputes = $query->paginate(20);

        return view('livewire.backend.dispute.index', compact('disputes'));
    }
}
