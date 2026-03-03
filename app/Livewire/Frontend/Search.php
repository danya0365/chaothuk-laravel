<?php

namespace App\Livewire\Frontend;

use App\Models\Recruit;
use App\Models\Work;
use Livewire\Component;

class Search extends Component
{
    public string $query = '';
    public string $type = 'works'; // works | recruits
    public ?array $results = null;
    public bool $searched = false;

    protected $queryString = ['query', 'type'];

    public function mount(): void
    {
        if ($this->query) $this->search();
    }

    public function search(): void
    {
        if (!trim($this->query)) return;
        $this->searched = true;

        if ($this->type === 'recruits') {
            $items = Recruit::with(['author', 'province', 'workType'])
                ->where(fn($q) =>
                    $q->where('title', 'like', "%{$this->query}%")
                      ->orWhere('description', 'like', "%{$this->query}%")
                )
                ->latest()->limit(24)->get();
        } else {
            $items = Work::with(['author', 'province', 'workType'])
                ->where(fn($q) =>
                    $q->where('title', 'like', "%{$this->query}%")
                      ->orWhere('description', 'like', "%{$this->query}%")
                )
                ->latest()->limit(24)->get();
        }

        $this->results = $items->toArray();
    }

    public function setType(string $type): void
    {
        $this->type = $type;
        if ($this->query) $this->search();
    }

    public function render()
    {
        return view('livewire.frontend.search')
            ->layout('frontend.layout', ['title' => 'ค้นหา — Chaothuk']);
    }
}
