<?php

namespace App\Livewire\Frontend;

use App\Models\Portfolio;
use App\Models\WorkType;
use Livewire\Component;

class PortfolioEdit extends Component
{
    public int $id;
    public string $title = '';
    public string $description = '';
    public ?int $workTypeId = null;
    public array $workTypes = [];

    public function mount(int $id): void
    {
        $portfolio = Portfolio::where('user_id', auth()->id())->findOrFail($id);
        $this->id = $id;
        $this->title = $portfolio->title;
        $this->description = $portfolio->description ?? '';
        $this->workTypeId = $portfolio->work_type_id;
        $this->workTypes = WorkType::orderBy('title')->get()->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|string',
            'workTypeId'  => 'nullable|exists:work_types,id',
        ]);

        $portfolio = Portfolio::where('user_id', auth()->id())->findOrFail($this->id);
        $portfolio->update([
            'title'        => $this->title,
            'description'  => $this->description,
            'work_type_id' => $this->workTypeId,
        ]);

        $this->redirect(route('frontend.portfolios'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.portfolio-edit')
            ->layout('frontend.layout', ['title' => 'แก้ไขผลงาน — Chaothuk']);
    }
}
