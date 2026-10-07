<?php

namespace App\Livewire\Frontend;

use App\Models\Portfolio;
use Livewire\Component;

class PortfolioDetail extends Component
{
    /**
     * The route parameter arrives as a string, and Livewire re-hydrates public
     * properties from the snapshot, so a typed int here fatals with
     * "Cannot assign string to property ... of type int". Route-model binding
     * passes the model instead. The property is named to avoid colliding with
     * the $portfolio array the blade reads.
     */
    public Portfolio $record;

    public ?array $portfolio = null;

    public array $userPortfolios = [];

    public function mount(Portfolio $record): void
    {
        $this->record = $record;
        $this->portfolio = $record->load(['user', 'workType'])->toArray();

        // Load other portfolios by the same user
        $this->userPortfolios = Portfolio::with('workType')
            ->where('user_id', $record->user_id)
            ->where('id', '!=', $record->id)
            ->latest()
            ->limit(6)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.portfolio-detail')
            ->layout('frontend.layout', ['title' => ($this->portfolio['title'] ?? 'ผลงาน').' — Chaothuk']);
    }
}
