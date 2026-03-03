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

    public string $tab = 'all';
    public string $search = '';
    public string $provinceId = '';
    public string $workTypeId = '';
    public string $sortBy = 'latest';

    protected $queryString = ['tab', 'search', 'provinceId', 'workTypeId', 'sortBy'];

    public function updatedTab(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedProvinceId(): void { $this->resetPage(); }
    public function updatedWorkTypeId(): void { $this->resetPage(); }

    public function deleteRecruit(int $recruitId): void
    {
        if (!auth()->check()) return;
        $recruit = Recruit::where('id', $recruitId)->where('author_id', auth()->id())->first();
        if ($recruit) {
            $recruit->delete();
        }
    }

    public function render()
    {
        $query = Recruit::with(['author', 'province', 'workType']);

        if ($this->tab === 'my' && auth()->check()) {
            $query = $query->where('author_id', auth()->id());
        }

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
            default       => $query->latest(),
        };

        $recruits   = $query->paginate(12);
        $provinces  = Province::orderBy('name_th')->get();
        $workTypes  = WorkType::orderBy('title')->get();

        $myRecruitsCount = auth()->check()
            ? Recruit::where('author_id', auth()->id())->count()
            : 0;

        return view('livewire.frontend.recruit-browse', compact('recruits', 'provinces', 'workTypes', 'myRecruitsCount'))
            ->layout('frontend.layout', ['title' => ($this->tab === 'my' ? 'ประกาศของฉัน' : 'รับสมัคร') . ' — Chaothuk']);
    }
}
