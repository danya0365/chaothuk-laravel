<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Models\WorkReview;
use App\Models\RecruitReview;
use Livewire\Component;

class ReviewSection extends Component
{
    // Props — set by parent
    public string $entityType;   // 'work' or 'recruit'
    public int $entityId;

    // New review form
    public bool $showReviewForm = false;
    public string $reviewTitle = '';
    public int $reviewRating = 5;
    public string $reviewContent = '';
    public ?string $reviewMessage = null;

    // Reply form
    public ?int $replyingTo = null;
    public string $replyContent = '';

    public function submitReview(): void
    {
        if (!auth()->check()) return;

        $this->validate([
            'reviewContent' => 'required|min:5',
            'reviewRating'  => 'required|integer|min:1|max:5',
        ]);

        $post = Post::create([
            'author_id' => auth()->id(),
            'title'     => $this->reviewTitle ?: null,
            'content'   => $this->reviewContent,
            'rating'    => $this->reviewRating,
            'images'    => [],
        ]);

        // Link to entity
        if ($this->entityType === 'work') {
            WorkReview::create(['work_id' => $this->entityId, 'post_id' => $post->id]);
        } else {
            RecruitReview::create(['recruit_id' => $this->entityId, 'post_id' => $post->id]);
        }

        $this->reviewMessage = '✅ รีวิวของคุณถูกบันทึกแล้ว';
        $this->reviewTitle = '';
        $this->reviewContent = '';
        $this->reviewRating = 5;
        $this->showReviewForm = false;
    }

    public function startReply(int $postId): void
    {
        $this->replyingTo = $this->replyingTo === $postId ? null : $postId;
        $this->replyContent = '';
    }

    public function submitReply(): void
    {
        if (!auth()->check() || !$this->replyingTo) return;

        $this->validate([
            'replyContent' => 'required|min:2',
        ]);

        Post::create([
            'author_id' => auth()->id(),
            'parent_id' => $this->replyingTo,
            'content'   => $this->replyContent,
            'images'    => [],
        ]);

        $this->replyingTo = null;
        $this->replyContent = '';
    }

    public function render()
    {
        $pivotTable = $this->entityType === 'work' ? 'works_reviews' : 'recruits_reviews';
        $fk = $this->entityType === 'work' ? 'work_id' : 'recruit_id';

        $postIds = \DB::table($pivotTable)->where($fk, $this->entityId)->pluck('post_id');

        // Recursive eager loading function for unlimited depth
        $eagerLoadReplies = function ($query) use (&$eagerLoadReplies) {
            $query->with(['author', 'replies' => $eagerLoadReplies])->latest();
        };

        $reviews = Post::with(['author', 'replies' => $eagerLoadReplies])
            ->whereIn('id', $postIds)
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('livewire.frontend.review-section', [
            'reviews' => $reviews,
        ]);
    }
}
