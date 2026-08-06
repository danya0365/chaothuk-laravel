<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Work;
use App\Models\Province;
use App\Models\Topic;
use App\Models\FeaturedWork;

#[Layout('layouts.landing')]
class Landing extends Component
{
    public array $stats = [];
    public array $featuredWorks = [];
    public array $latestWorks = [];

    public function mount()
    {
        // Platform Stats
        $this->stats = [
            'users_count' => User::count(),
            'works_count' => Work::count(),
            'provinces_count' => Province::count(),
        ];

        // Featured Works
        $this->featuredWorks = FeaturedWork::active()
            ->with(['work.author', 'work.province', 'work.workType'])
            ->orderBy('slot_position')
            ->limit(4)
            ->get()
            ->map(fn($f) => [
                'id'            => $f->work->id,
                'title'         => $f->work->title,
                'price'         => $f->work->price,
                'primary_image' => image_url($f->work->primary_image),
                'province'      => $f->work->province?->name_th,
                'type'          => $f->work->workType?->title,
                'rating'        => $f->work->avg_review_rating,
                'author_name'   => $f->work->author?->name,
                'author_avatar' => $f->work->author?->getAvatar(32) ?? '',
            ])
            ->toArray();

        // Latest Works
        $this->latestWorks = Work::with(['author', 'province', 'workType'])
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn($w) => [
                'id'           => $w->id,
                'title'        => $w->title,
                'price'        => $w->price,
                'primary_image'=> image_url($w->primary_image),
                'province'     => $w->province?->name_th,
                'type'         => $w->workType?->title,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.landing');
    }
}
