<?php

namespace App\Livewire\Backend\Reputation;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserReputation;
use App\Models\UserBadge;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $trustFilter = '';
    public $badgeFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedTrustFilter()
    {
        $this->resetPage();
    }

    public function updatedBadgeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = UserReputation::query()
            ->with(['user', 'user.badges']);

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->trustFilter) {
            $query->where('trust_level', $this->trustFilter);
        }

        if ($this->badgeFilter) {
            $query->whereHas('user.badges', function ($q) {
                $q->where('badge_type', $this->badgeFilter);
            });
        }

        $reputations = $query->orderBy('overall_score', 'desc')->paginate(20);

        // Required data for filters
        $trustLevels = UserReputation::select('trust_level')->distinct()->pluck('trust_level');
        $badgeTypes = UserBadge::select('badge_type')->distinct()->pluck('badge_type');

        return view('livewire.backend.reputation.index', compact('reputations', 'trustLevels', 'badgeTypes'))
            ->layout('layouts.backend', ['title' => 'ระบบชื่อเสียง (Reputation & Badges)']);
    }
}
