<?php

namespace App\Livewire\Backend\Portfolio;

use App\Models\Portfolio;
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
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete(); // Soft Deletes enabled
        
        session()->flash('success', 'ลบแฟ้มผลงานเรียบร้อยแล้ว');
    }

    public function render()
    {
        $portfolios = Portfolio::with(['user', 'workType'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.backend.portfolio.index', [
            'portfolios' => $portfolios,
        ]);
    }
}
