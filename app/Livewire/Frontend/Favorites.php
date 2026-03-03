<?php

namespace App\Livewire\Frontend;

use App\Models\Favorite;
use App\Models\Work;
use App\Models\Recruit;
use Livewire\Component;

class Favorites extends Component
{
    public string $tab = 'work';
    public array $items = [];

    public function mount(): void
    {
        $this->loadFavorites();
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->loadFavorites();
    }

    public function removeFavorite(int $id): void
    {
        Favorite::where('id', $id)->where('user_id', auth()->id())->delete();
        $this->loadFavorites();
    }

    public function loadFavorites(): void
    {
        $uid = auth()->id();
        $type = $this->tab === 'work' ? Work::class : Recruit::class;

        $this->items = Favorite::with('favoritable')
            ->where('user_id', $uid)
            ->where('favoritable_type', $type)
            ->latest()
            ->limit(50)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.frontend.favorites')
            ->layout('frontend.layout', ['title' => 'รายการโปรด — Chaothuk']);
    }
}
