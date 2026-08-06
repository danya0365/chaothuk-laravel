<?php

namespace App\Livewire\Frontend;

use App\Models\Category;
use App\Models\Work;
use App\Models\Recruit;
use Livewire\Component;

class CategoryBrowse extends Component
{
    public ?array $categories = null;
    public ?int $selectedCategoryId = null;
    public ?array $selectedCategory = null;
    public ?array $works = null;
    public ?array $recruits = null;

    public function mount(): void
    {
        $this->categories = Category::orderBy('name', 'asc')->get()->toArray();
    }

    public function selectCategory(int $id): void
    {
        $this->selectedCategoryId = $id;
        $this->selectedCategory = collect($this->categories)->firstWhere('id', $id);

        $this->works = Work::with(['province', 'workType', 'author'])
            ->whereHas('categories', fn($q) => $q->where('categories.id', $id))
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn($w) => [
                'id'            => $w->id,
                'title'         => $w->title,
                'price'         => $w->price,
                'primary_image' => image_url($w->primary_image),
                'province'      => $w->province?->name_th,
                'work_type'     => $w->workType?->title,
            ])
            ->toArray();

        $this->recruits = Recruit::with(['province', 'workType', 'author'])
            ->whereHas('categories', fn($q) => $q->where('categories.id', $id))
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn($r) => [
                'id'            => $r->id,
                'title'         => $r->title,
                'budget'        => $r->budget,
                'primary_image' => image_url($r->primary_image),
                'province'      => $r->province?->name_th,
            ])
            ->toArray();
    }

    public function clearCategory(): void
    {
        $this->selectedCategoryId = null;
        $this->selectedCategory = null;
        $this->works = null;
        $this->recruits = null;
    }

    public function render()
    {
        return view('livewire.frontend.category-browse')
            ->layout('frontend.layout', ['title' => 'หมวดหมู่ — Chaothuk']);
    }
}
