<?php

namespace App\Livewire\Frontend;

use App\Http\Resources\UserResource;
use App\Http\Resources\WorkCollection;
use App\Http\Resources\RecruitCollection;
use App\Http\Resources\WorkBookingCollection;
use App\Http\Resources\RecruitBookingCollection;
use App\Http\Resources\PostCollection;
use App\Models\User;
use App\Models\Work;
use App\Models\Recruit;
use App\Models\WorkBooking;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MeExplorer extends Component
{
    public ?array $profile = null;
    public ?array $myWorks = null;
    public ?array $myRecruits = null;
    public ?array $myBookings = null;
    public ?array $myPosts = null;
    public ?string $errorMessage = null;
    public bool $isLoading = false;
    public string $activeTab = 'profile';

    protected function apiUser(): ?User
    {
        if (!session('api_token')) return null;

        // Find the user whose token matches the session token
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken(session('api_token'));
        return $token?->tokenable;
    }

    public function loadProfile(): void
    {
        $this->isLoading = true;
        $this->errorMessage = null;

        $user = $this->apiUser();
        if (!$user) {
            $this->errorMessage = 'No API token. Please login on the Dashboard page first.';
            $this->isLoading = false;
            return;
        }

        try {
            $this->profile = ['data' => (new UserResource($user->fresh()))->resolve()];
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function loadMyWorks(): void
    {
        $this->isLoading = true;
        $user = $this->apiUser();
        if (!$user) { $this->isLoading = false; return; }

        try {
            $paginated = Work::with(['author', 'province', 'workType', 'categories'])
                ->where('author_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $this->myWorks = (new WorkCollection($paginated))->response()->getData(true);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function loadMyRecruits(): void
    {
        $this->isLoading = true;
        $user = $this->apiUser();
        if (!$user) { $this->isLoading = false; return; }

        try {
            $paginated = Recruit::with(['author', 'province', 'workType', 'categories'])
                ->where('author_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $this->myRecruits = (new RecruitCollection($paginated))->response()->getData(true);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function loadMyBookings(): void
    {
        $this->isLoading = true;
        $user = $this->apiUser();
        if (!$user) { $this->isLoading = false; return; }

        try {
            $paginated = WorkBooking::with(['work', 'author'])
                ->where('author_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $this->myBookings = (new WorkBookingCollection($paginated))->response()->getData(true);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function loadMyPosts(): void
    {
        $this->isLoading = true;
        $user = $this->apiUser();
        if (!$user) { $this->isLoading = false; return; }

        try {
            $paginated = Post::with(['author'])
                ->where('author_id', $user->id)
                ->whereNull('parent_id')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $this->myPosts = (new PostCollection($paginated))->response()->getData(true);
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        match ($tab) {
            'profile'  => $this->loadProfile(),
            'works'    => $this->loadMyWorks(),
            'recruits' => $this->loadMyRecruits(),
            'bookings' => $this->loadMyBookings(),
            'posts'    => $this->loadMyPosts(),
            default    => null,
        };
    }

    public function mount(): void
    {
        $this->loadProfile();
    }

    public function render()
    {
        return view('livewire.frontend.me-explorer')
            ->layout('frontend.layout', ['title' => 'Me — Chaothuk Frontend']);
    }
}
