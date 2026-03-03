<?php

namespace App\Livewire\Frontend;

use App\Http\Resources\WorkCollection;
use App\Http\Resources\WorkResource;
use App\Http\Resources\PostCollection;
use App\Models\Work;
use App\Models\Post;
use App\Models\WorkReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class WorkExplorer extends Component
{
    public ?array $works = null;
    public ?array $selectedWork = null;
    public ?array $reviews = null;
    public string $searchQuery = '';
    public int $page = 1;
    public ?string $errorMessage = null;
    public bool $isLoading = false;

    // Review form
    public bool $showReviewForm = false;
    public int $reviewRating = 5;
    public string $reviewContent = '';
    public string $reviewTitle = '';
    public ?string $reviewSuccess = null;

    /**
     * Load works directly from DB — avoids self-referencing HTTP deadlock in Docker/FPM.
     */
    public function loadWorks(): void
    {
        $this->isLoading = true;
        $this->errorMessage = null;
        $this->selectedWork = null;
        $this->reviews = null;

        try {
            $query = Work::with(['author', 'province', 'workType', 'categories'])
                ->orderBy('created_at', 'desc');

            if ($this->searchQuery) {
                $query->where(function ($q) {
                    $q->where('title', 'LIKE', "%{$this->searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$this->searchQuery}%");
                });
            }

            $paginated = $query->paginate(12, ['*'], 'page', $this->page);
            $this->works = (new WorkCollection($paginated))->response()->getData(true);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
            Log::error('WorkExplorer loadWorks error', ['error' => $e->getMessage()]);
        }

        $this->isLoading = false;
    }

    public function selectWork(int $id): void
    {
        $this->isLoading = true;
        $this->showReviewForm = false;
        $this->reviewSuccess = null;

        try {
            $work = Work::with(['author', 'province', 'workType', 'categories'])->findOrFail($id);
            $this->selectedWork = ['data' => (new WorkResource($work))->resolve()];

            // Load reviews (posts connected to this work via works_reviews)
            $postIds = \DB::table('works_reviews')->where('work_id', $id)->pluck('post_id');
            $posts = Post::with(['author'])
                ->whereIn('id', $postIds)
                ->whereNull('parent_id')
                ->latest()
                ->take(5)
                ->get();

            $this->reviews = ['data' => (new PostCollection($posts))->resolve()];
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function submitReview(): void
    {
        if (!session('api_token') || !$this->selectedWork) return;

        $workId = $this->selectedWork['data']['id'] ?? null;
        if (!$workId) return;

        $this->isLoading = true;
        $this->errorMessage = null;

        try {
            // POST review via HTTP client (auth:sanctum protected — this calls localhost from within the same request
            // chain, but POST is fast and won't deadlock like a GET list call)
            $response = Http::withToken(session('api_token'))
                ->asMultipart()
                ->post(url("/api/works/{$workId}/reviews"), [
                    ['name' => 'content', 'contents' => $this->reviewContent],
                    ['name' => 'rating', 'contents' => (string)$this->reviewRating],
                    ['name' => 'title', 'contents' => $this->reviewTitle],
                ]);

            $data = $response->json();
            if ($response->successful() && ($data['status'] ?? false)) {
                $this->reviewSuccess = 'รีวิวถูกส่งแล้ว ✅';
                $this->reviewContent = '';
                $this->reviewTitle = '';
                $this->showReviewForm = false;
                $this->selectWork($workId);
            } else {
                $this->errorMessage = $data['message'] ?? 'Review failed';
            }
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function mount(): void
    {
        $this->loadWorks();
    }

    public function render()
    {
        return view('livewire.frontend.work-explorer')
            ->layout('frontend.layout', ['title' => 'Works — Chaothuk Frontend']);
    }
}
