<?php

namespace App\Livewire\Backend\User\Partials;

use App\Models\User;
use App\Models\UserBadge;
use App\Enums\BadgeType;
use Livewire\Component;

class BadgesManager extends Component
{
    public User $user;
    
    // Form State
    public $showModal = false;
    public $selectedBadge = '';
    public $badgeLevel = 1;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function openModal()
    {
        $this->reset(['selectedBadge']);
        $this->badgeLevel = 1;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function assignBadge()
    {
        $this->validate([
            'selectedBadge' => 'required|string',
            'badgeLevel' => 'required|integer|min:1',
        ]);

        // Check if user already has this badge
        $existing = UserBadge::where('user_id', $this->user->id)
            ->where('badge_type', $this->selectedBadge)
            ->first();

        if ($existing) {
            $existing->update(['badge_level' => $this->badgeLevel]);
            session()->flash('success', 'อัปเดตระดับตราสัญลักษณ์เรียบร้อยแล้ว');
        } else {
            UserBadge::create([
                'user_id' => $this->user->id,
                'badge_type' => $this->selectedBadge,
                'badge_level' => $this->badgeLevel,
            ]);
            session()->flash('success', 'มอบตราสัญลักษณ์เรียบร้อยแล้ว');
        }

        $this->closeModal();
        $this->user->refresh(); // refresh to update badges relation
    }

    public function removeBadge($badgeId)
    {
        $badge = UserBadge::where('id', $badgeId)->where('user_id', $this->user->id)->first();
        if ($badge) {
            $badge->delete();
            session()->flash('success', 'เพิกถอนตราสัญลักษณ์เรียบร้อยแล้ว');
            $this->user->refresh();
        }
    }

    public function render()
    {
        $badges = $this->user->badges;
        $availableBadges = BadgeType::cases();

        return view('livewire.backend.user.partials.badges-manager', [
            'badges' => $badges,
            'availableBadges' => $availableBadges,
        ]);
    }
}
