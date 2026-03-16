<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Models\Portfolio;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use Livewire\Component;

class Profile extends Component
{
    public string $tab = 'info';

    // Tab data
    public ?array $myWorks = null;
    public ?array $myRecruits = null;
    public ?array $myWorkBookings = null;
    public ?array $myRecruitBookings = null;
    public ?array $myReviews = null;
    public ?array $myPortfolios = null;

    public function mount(): void
    {
        $this->loadTab();
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->loadTab();
    }

    protected function loadTab(): void
    {
        $uid = auth()->id();
        match ($this->tab) {
            'works'      => $this->myWorks = Work::with(['province', 'workType', 'activeFeature'])
                                ->where('author_id', $uid)->latest()->limit(20)->get()->toArray(),
            'recruits'   => $this->myRecruits = Recruit::with(['province', 'workType'])
                                ->where('author_id', $uid)->latest()->limit(20)->get()->toArray(),
            'bookings'   => $this->loadBookings($uid),
            'reviews'    => $this->myReviews = Post::where('author_id', $uid)
                                ->whereNull('parent_id')->where('rating', '>', 0)
                                ->latest()->limit(20)->get()->toArray(),
            'portfolios' => $this->myPortfolios = Portfolio::with('workType')
                                ->where('user_id', $uid)->latest()->limit(20)->get()->toArray(),
            default      => null,
        };
    }

    protected function loadBookings(int $uid): void
    {
        $this->myWorkBookings = WorkBooking::with(['work'])
            ->where('author_id', $uid)->latest()->limit(10)->get()->toArray();
        $this->myRecruitBookings = RecruitBooking::with(['recruit'])
            ->where('author_id', $uid)->latest()->limit(10)->get()->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.profile')
            ->layout('frontend.layout', ['title' => 'โปรไฟล์ — Chaothuk']);
    }
}
