<?php

namespace App\Livewire\Backend\WorkType;

use App\Models\WorkType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $workType = WorkType::findOrFail($id);
        
        // Remove image if exists? SoftDeletes is enabled, so maybe just soft delete.
        $workType->delete();

        session()->flash('success', 'ลบประเภทงานเรียบร้อยแล้ว');
    }

    public function render()
    {
        $workTypes = WorkType::where('title', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.backend.work-type.index', [
            'workTypes' => $workTypes,
        ]);
    }
}
