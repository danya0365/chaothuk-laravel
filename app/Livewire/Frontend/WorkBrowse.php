<?php

namespace App\Livewire\Frontend;

use App\Models\Province;
use App\Models\Work;
use App\Models\WorkLike;
use App\Models\WorkType;
use Livewire\Component;
use Livewire\WithPagination;

class WorkBrowse extends Component
{
    use WithPagination;

    public string $tab = 'all';   // 'all' or 'my'
    public string $search = '';
    public string $provinceId = '';
    public string $workTypeId = '';
    public string $sortBy = 'latest';
    public ?float $userLat = null;
    public ?float $userLng = null;

    protected $queryString = ['tab', 'search', 'provinceId', 'workTypeId', 'sortBy', 'userLat', 'userLng'];

    public function updatedTab(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedProvinceId(): void { $this->resetPage(); }
    public function updatedWorkTypeId(): void { $this->resetPage(); }

    public function toggleLike(int $workId): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }
        $existing = WorkLike::where('author_id', auth()->id())->where('work_id', $workId)->first();
        if ($existing) {
            $existing->delete();
            Work::where('id', $workId)->decrement('like_count');
        } else {
            WorkLike::create(['author_id' => auth()->id(), 'work_id' => $workId]);
            Work::where('id', $workId)->increment('like_count');
        }
    }

    public function deleteWork(int $workId): void
    {
        if (!auth()->check()) return;
        $work = Work::where('id', $workId)->where('author_id', auth()->id())->first();
        if ($work) {
            $work->delete();
        }
    }

    public function render()
    {
        $query = Work::with(['author', 'province', 'workType']);

        // Tab filter
        if ($this->tab === 'my' && auth()->check()) {
            $query = $query->where('author_id', auth()->id());
        }

        // Search & filters
        $query = $query
            ->when($this->search, fn($q) => $q->where(fn($q2) =>
                $q2->where('title', 'like', "%{$this->search}%")
                   ->orWhere('description', 'like', "%{$this->search}%")
            ))
            ->when($this->provinceId, fn($q) => $q->where('province_id', $this->provinceId))
            ->when($this->workTypeId, fn($q) => $q->where('work_type_id', $this->workTypeId));

        $query = match($this->sortBy) {
            'popular' => $query->orderByDesc('like_count'),
            'rating'  => $query->orderByDesc('avg_review_rating'),
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'distance' => $query->when($this->userLat !== null && $this->userLng !== null, function ($q) {
                return $q->selectRaw('works.*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$this->userLat, $this->userLng, $this->userLat])
                         ->orderBy('distance');
            }, fn($q) => $q->latest()),
            default   => $query->latest(),
        };

        // If not using distance, make sure we select works.* so we don't accidentally get partial columns
        // Actually, Eloquent defaults to `select *` if no select is provided.
        // But let's ensure works.* is explicitly passed if distance wasn't selected yet we need to avoid ambiguous columns
        if ($this->sortBy !== 'distance') {
            $query->select('works.*');
        }

        $works     = $query->paginate(12);
        $provinces = Province::orderBy('name_th')->get();
        $workTypes = WorkType::orderBy('title')->get();

        $likedIds = auth()->check()
            ? WorkLike::where('author_id', auth()->id())->pluck('work_id')->toArray()
            : [];

        // Stats for "my" tab
        $myWorksCount = auth()->check()
            ? Work::where('author_id', auth()->id())->count()
            : 0;

        return view('livewire.frontend.work-browse', compact('works', 'provinces', 'workTypes', 'likedIds', 'myWorksCount'))
            ->layout('frontend.layout', ['title' => ($this->tab === 'my' ? 'งานของฉัน' : 'งาน') . ' — Chaothuk']);
    }
}
