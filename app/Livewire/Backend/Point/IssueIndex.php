<?php

namespace App\Livewire\Backend\Point;

use App\Models\IssuePoint;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('ระบบจัดการพอยท์ (Issue Points) - Admin')]
class IssueIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = ''; // For filtering by status

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deleteIssue($id)
    {
        $issue = IssuePoint::findOrFail($id);
        $issue->delete();
        session()->flash('success', 'ลบแคมเปญแจกพอยท์เรียบร้อยแล้ว');
    }

    public function render()
    {
        $issues = IssuePoint::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.backend.point.issue-index', [
            'issues' => $issues
        ]);
    }
}
