<?php

namespace App\Livewire\Frontend;

use App\Models\Portfolio;
use Livewire\Component;

class MyPortfolios extends Component
{
    public array $portfolios = [];
    public ?string $flashMessage = null;

    public function mount(): void
    {
        $this->loadPortfolios();
    }

    public function deletePortfolio(int $id): void
    {
        Portfolio::where('id', $id)->where('user_id', auth()->id())->delete();
        $this->flashMessage = '✅ ลบผลงานเรียบร้อย';
        $this->loadPortfolios();
    }

    protected function loadPortfolios(): void
    {
        $this->portfolios = Portfolio::with('workType')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.my-portfolios')
            ->layout('frontend.layout', ['title' => 'ผลงานของฉัน — Chaothuk']);
    }
}
