<?php

namespace App\Livewire\Frontend;

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
    public string $sortBy = 'latest';
    public ?float $userLat = null;
    public ?float $userLng = null;

    protected $queryString = ['search', 'provinceId', 'workTypeId', 'sortBy', 'userLat', 'userLng'];
    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedProvinceId(): void { $this->resetPage(); }
    public function updatedWorkTypeId(): void { $this->resetPage(); }



    public function render()
    {
        $query = Recruit::with(['author', 'province', 'workType']);

        $query = $query
            ->when($this->search, fn($q) => $q->where(fn($q2) =>
                $q2->where('title', 'like', "%{$this->search}%")
                   ->orWhere('description', 'like', "%{$this->search}%")
            ))
            ->when($this->provinceId, fn($q) => $q->where('province_id', $this->provinceId))
            ->when($this->workTypeId, fn($q) => $q->where('work_type_id', $this->workTypeId));

        $query = match($this->sortBy) {
            'budget_asc'  => $query->orderBy('budget'),
            'budget_desc' => $query->orderByDesc('budget'),
            'distance' => $query->when($this->userLat !== null && $this->userLng !== null, function ($q) {
                return $q->selectRaw('recruits.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$this->userLat, $this->userLng, $this->userLat])
                         ->orderBy('distance');
            }, fn($q) => $q->latest()),
            default       => $query->latest(),
        };

        if ($this->sortBy !== 'distance') {
            $query->select('recruits.*');
        }

        $recruits   = $query->paginate(12);
        $provinces  = Province::orderBy('name_th')->get();
        $workTypes  = WorkType::orderBy('title')->get();

        return view('livewire.frontend.recruit-browse', compact('recruits', 'provinces', 'workTypes'))
            ->layout('frontend.layout', ['title' => 'รับสมัคร — Chaothuk']);
    }
}
