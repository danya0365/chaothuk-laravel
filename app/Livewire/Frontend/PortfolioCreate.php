<?php

namespace App\Livewire\Frontend;

use App\Models\Portfolio;
use App\Models\WorkType;
use Livewire\Component;

class PortfolioCreate extends Component
{
    public string $title = '';
    public string $description = '';
    public ?int $workTypeId = null;
    public array $workTypes = [];

    public function mount(): void
    {
        $this->workTypes = WorkType::orderBy('title')->get()->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'title'       => 'required|min:3|max:255',
            'description' => 'nullable|string',
            'workTypeId'  => 'nullable|exists:work_types,id',
        ]);

        Portfolio::create([
            'user_id'      => auth()->id(),
            'title'        => $this->title,
            'description'  => $this->description,
            'work_type_id' => $this->workTypeId,
            'images'       => [],
        ]);

        $this->redirect(route('frontend.portfolios'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.portfolio-create')
            ->layout('frontend.layout', ['title' => 'เพิ่มผลงาน — Chaothuk']);
    }
}
