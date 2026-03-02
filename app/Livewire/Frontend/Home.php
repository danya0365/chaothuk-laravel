<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\Recruit;
use Livewire\Component;

class Home extends Component
{
    public array $latestWorks = [];
    public array $latestRecruits = [];

    public function mount(): void
    {
        $this->latestWorks = Work::with(['author', 'province', 'workType'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($w) => [
                'id'           => $w->id,
                'title'        => $w->title,
                'price'        => $w->price,
                'primary_image'=> $w->primary_image,
                'province'     => ['name_th' => $w->province?->name_th],
            ])
            ->toArray();

        $this->latestRecruits = Recruit::with(['author', 'province', 'workType'])
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn($r) => [
                'id'           => $r->id,
                'title'        => $r->title,
                'budget'       => $r->budget,
                'primary_image'=> $r->primary_image,
                'province'     => ['name_th' => $r->province?->name_th],
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.home')
            ->layout('frontend.layout', ['title' => 'หน้าแรก — Chaothuk']);
    }
}
