<?php

namespace App\Livewire\Frontend;

use App\Http\Resources\RecruitCollection;
use App\Http\Resources\RecruitResource;
use App\Http\Resources\PostCollection;
use App\Models\Recruit;
use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RecruitExplorer extends Component
{
    public ?array $recruits = null;
    public ?array $selectedRecruit = null;
    public ?array $reviews = null;
    public int $page = 1;
    public ?string $errorMessage = null;
    public bool $isLoading = false;

    public bool $showReviewForm = false;
    public int $reviewRating = 5;
    public string $reviewContent = '';
    public ?string $reviewSuccess = null;

    public function loadRecruits(): void
    {
        $this->isLoading = true;
        $this->errorMessage = null;
        $this->selectedRecruit = null;
        $this->reviews = null;

        try {
            $paginated = Recruit::with(['author', 'province', 'workType', 'categories'])
                ->orderBy('created_at', 'desc')
                ->paginate(12, ['*'], 'page', $this->page);

            $this->recruits = (new RecruitCollection($paginated))->response()->getData(true);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
            Log::error('RecruitExplorer loadRecruits error', ['error' => $e->getMessage()]);
        }

        $this->isLoading = false;
    }

    public function selectRecruit(int $id): void
    {
        $this->isLoading = true;
        $this->showReviewForm = false;
        $this->reviewSuccess = null;

        try {
            $recruit = Recruit::with(['author', 'province', 'workType', 'categories'])->findOrFail($id);
            $this->selectedRecruit = ['data' => (new RecruitResource($recruit))->resolve()];

            $postIds = \DB::table('recruits_reviews')->where('recruit_id', $id)->pluck('post_id');
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
        if (!session('api_token') || !$this->selectedRecruit) return;

        $recruitId = $this->selectedRecruit['data']['id'] ?? null;
        if (!$recruitId) return;

        $this->isLoading = true;

        try {
            $response = Http::withToken(session('api_token'))
                ->asMultipart()
                ->post(url("/api/recruits/{$recruitId}/reviews"), [
                    ['name' => 'content', 'contents' => $this->reviewContent],
                    ['name' => 'rating', 'contents' => (string)$this->reviewRating],
                ]);

            $data = $response->json();
            if ($response->successful() && ($data['status'] ?? false)) {
                $this->reviewSuccess = 'รีวิวถูกส่งแล้ว ✅';
                $this->reviewContent = '';
                $this->showReviewForm = false;
                $this->selectRecruit($recruitId);
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
        $this->loadRecruits();
    }

    public function render()
    {
        return view('livewire.frontend.recruit-explorer')
            ->layout('frontend.layout', ['title' => 'Recruits — Chaothuk Frontend']);
    }
}
