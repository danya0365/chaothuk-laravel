<?php

namespace App\Livewire\Frontend;

use App\Http\Resources\RecruitCollection;
use App\Models\Province;
use App\Models\Recruit;
use App\Models\WorkType;
use Livewire\Component;
use Livewire\WithPagination;

class RecruitBrowse extends Component
{
    use WithPagination;

    public string $search = '';
    public string $provinceId = '';
    public string $workTypeId = '';

    protected $queryString = ['search', 'provinceId', 'workTypeId'];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedProvinceId(): void { $this->resetPage(); }

    public function render()
    {
        $recruits = Recruit::with(['author', 'province', 'workType'])
            ->when($this->search, fn($q) => $q->where(fn($q2) =>
                $q2->where('title', 'like', "%{$this->search}%")
                   ->orWhere('description', 'like', "%{$this->search}%")
            ))
            ->when($this->provinceId, fn($q) => $q->where('province_id', $this->provinceId))
            ->when($this->workTypeId, fn($q) => $q->where('work_type_id', $this->workTypeId))
            ->latest()
            ->paginate(12);

        $provinces = Province::orderBy('name_th')->get();
        $workTypes = WorkType::orderBy('title')->get();

        return view('livewire.frontend.recruit-browse', compact('recruits', 'provinces', 'workTypes'))
            ->layout('frontend.layout', ['title' => 'รับสมัคร — Chaothuk']);
    }
}
