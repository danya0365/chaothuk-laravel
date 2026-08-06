<?php

namespace App\Livewire\Frontend;

use App\Models\Banner;
use App\Models\FeaturedWork;
use App\Models\ProvinceTopWork;
use App\Models\Work;
use App\Models\Recruit;
use Livewire\Component;

class Home extends Component
{
    public array $featuredWorks = [];
    public array $provinceTopWorks = [];
    public array $latestWorks = [];
    public array $latestRecruits = [];
    public array $banners = [];

    public function mount(): void
    {
        // Featured works carousel
        $this->featuredWorks = FeaturedWork::active()
            ->with(['work.author', 'work.province', 'work.workType'])
            ->orderBy('slot_position')
            ->limit(10)
            ->get()
            ->map(fn($f) => [
                'id'            => $f->work->id,
                'title'         => $f->work->title,
                'price'         => $f->work->price,
                'primary_image' => image_url($f->work->primary_image),
                'province'      => $f->work->province?->name_th,
                'type'          => $f->work->workType?->title,
                'rating'        => $f->work->avg_review_rating,
                'likes'         => $f->work->like_count ?? 0,
                'author_name'   => $f->work->author?->name,
                'author_avatar' => $f->work->author?->getAvatar(32) ?? '',
            ])
            ->toArray();

        $this->latestWorks = Work::with(['author', 'province', 'workType'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($w) => [
                'id'           => $w->id,
                'title'        => $w->title,
                'price'        => $w->price,
                'primary_image'=> image_url($w->primary_image),
                'province'     => ['name_th' => $w->province?->name_th],
                'rating'       => $w->avg_review_rating,
                'likes'        => $w->like_count ?? 0,
                'type'         => $w->workType?->title,
            ])
            ->toArray();

        // Province Top Works (monthly)
        $this->provinceTopWorks = ProvinceTopWork::currentMonth()
            ->topOnly()
            ->with(['work.author', 'work.workType', 'province'])
            ->orderByDesc('total_score')
            ->get()
            ->map(fn($pt) => [
                'id'            => $pt->work->id,
                'title'         => $pt->work->title,
                'price'         => $pt->work->price,
                'primary_image' => image_url($pt->work->primary_image),
                'province'      => $pt->province?->name_th,
                'type'          => $pt->work->workType?->title,
                'rating'        => $pt->avg_rating,
                'score'         => $pt->total_score,
                'bookings'      => $pt->booking_count,
                'author_name'   => $pt->work->author?->name,
                'author_avatar' => $pt->work->author?->getAvatar(32) ?? '',
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
                'primary_image'=> image_url($r->primary_image),
                'province'     => ['name_th' => $r->province?->name_th],
            ])
            ->toArray();

        $this->banners = Banner::where('is_public', true)
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.home')
            ->layout('frontend.layout', ['title' => 'หน้าแรก — Chaothuk']);
    }
}
