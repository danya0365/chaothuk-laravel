<?php

namespace App\Livewire\Frontend;

use App\Models\Portfolio;
use Livewire\Component;

class PortfolioDetail extends Component
{
    public int $id;
    public ?array $portfolio = null;
    public array $userPortfolios = [];

    public function mount(int $id): void
    {
        $this->id = $id;
        $p = Portfolio::with(['user', 'workType'])->findOrFail($id);
        $this->portfolio = $p->toArray();

        // Load other portfolios by the same user
        $this->userPortfolios = Portfolio::with('workType')
            ->where('user_id', $p->user_id)
            ->where('id', '!=', $id)
            ->latest()
            ->limit(6)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.portfolio-detail')
            ->layout('frontend.layout', ['title' => ($this->portfolio['title'] ?? 'ผลงาน') . ' — Chaothuk']);
    }
}
