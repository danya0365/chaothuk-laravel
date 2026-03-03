<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\Recruit;
use Livewire\Component;

class MapExplore extends Component
{
    public array $works = [];
    public array $recruits = [];

    public function mount(): void
    {
        $this->works = Work::with(['province', 'workType'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'title', 'price', 'primary_image', 'latitude', 'longitude', 'province_id', 'work_type_id', 'avg_review_rating')
            ->limit(500)
            ->get()
            ->map(fn($w) => [
                'id'        => $w->id,
                'title'     => $w->title,
                'price'     => $w->price,
                'image'     => $w->primary_image,
                'lat'       => (float) $w->latitude,
                'lng'       => (float) $w->longitude,
                'province'  => $w->province?->name_th,
                'type'      => $w->workType?->title ?? $w->workType?->title,
                'rating'    => $w->avg_review_rating,
                'kind'      => 'work',
            ])
            ->toArray();

        $this->recruits = Recruit::with(['province', 'workType'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'title', 'budget', 'primary_image', 'latitude', 'longitude', 'province_id', 'work_type_id')
            ->limit(500)
            ->get()
            ->map(fn($r) => [
                'id'        => $r->id,
                'title'     => $r->title,
                'price'     => $r->budget,
                'image'     => $r->primary_image,
                'lat'       => (float) $r->latitude,
                'lng'       => (float) $r->longitude,
                'province'  => $r->province?->name_th,
                'type'      => $r->workType?->title ?? $r->workType?->title,
                'rating'    => 0,
                'kind'      => 'recruit',
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.map-explore')
            ->layout('frontend.layout', ['title' => 'แผนที่งาน — Chaothuk']);
    }
}
