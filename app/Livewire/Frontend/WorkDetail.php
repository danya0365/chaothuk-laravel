<?php

namespace App\Livewire\Frontend;

use App\Models\Work;
use App\Models\Post;
use App\Models\WorkBooking;
use App\Models\WorkLike;
use App\Models\WorkReview;
use Livewire\Component;

class WorkDetail extends Component
{
    public int $id;
    public ?Work $work = null;

    // Reviews
    public ?array $reviews = null;

    // Review form
    public bool $showReviewForm = false;
    public int $reviewRating = 5;
    public string $reviewTitle = '';
    public string $reviewContent = '';
    public ?string $reviewMessage = null;

    // Booking form
    public bool $showBookingForm = false;
    public string $bookingMessage = '';
    public string $bookingPhone = '';
    public string $bookingDate = '';
    public ?string $bookingMessage2 = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->work = Work::with(['author', 'province', 'workType', 'categories'])->findOrFail($id);
        $this->loadReviews();
    }

    public function loadReviews(): void
    {
        $postIds = \DB::table('works_reviews')->where('work_id', $this->id)->pluck('post_id');
        $this->reviews = Post::with(['author', 'replies.author'])
            ->whereIn('id', $postIds)
            ->whereNull('parent_id')
            ->latest()
            ->get()
            ->toArray();
    }

    public function toggleLike(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }
        $existing = WorkLike::where('author_id', auth()->id())->where('work_id', $this->id)->first();
        if ($existing) {
            $existing->delete();
            $this->work->decrement('like_count');
        } else {
            WorkLike::create(['author_id' => auth()->id(), 'work_id' => $this->id]);
            $this->work->increment('like_count');
        }
        $this->work->refresh();
    }

    public function submitReview(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }

        $this->validate([
            'reviewContent' => 'required|min:5',
            'reviewRating'  => 'required|integer|min:1|max:5',
        ]);

        $post = Post::create([
            'author_id' => auth()->id(),
            'title'     => $this->reviewTitle ?: null,
            'content'   => $this->reviewContent,
            'rating'    => $this->reviewRating,
            'images'    => json_encode([]),
        ]);

        WorkReview::create(['work_id' => $this->id, 'post_id' => $post->id]);

        // Update avg rating
        $avg = \DB::table('works_reviews')
            ->join('posts', 'posts.id', '=', 'works_reviews.post_id')
            ->where('works_reviews.work_id', $this->id)
            ->whereNull('posts.parent_id')
            ->avg('posts.rating');
        $this->work->update(['avg_review_rating' => round($avg, 1), 'reply_count' => \DB::table('works_reviews')->where('work_id', $this->id)->count()]);

        $this->reviewMessage = '✅ รีวิวของคุณถูกบันทึกแล้ว';
        $this->reviewContent = '';
        $this->reviewTitle = '';
        $this->showReviewForm = false;
        $this->loadReviews();
    }

    public function submitBooking(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }

        $this->validate([
            'bookingPhone'   => 'required|min:9',
            'bookingDate'    => 'required|date',
            'bookingMessage' => 'nullable|string',
        ]);

        WorkBooking::create([
            'author_id'        => auth()->id(),
            'work_id'          => $this->id,
            'mobile_phone'     => $this->bookingPhone,
            'booking_date'     => $this->bookingDate,
            'customer_message' => $this->bookingMessage,
            'booking_status'   => 'waiting-to-confirm',
        ]);

        $this->bookingMessage2 = '✅ ส่งคำขอจองแล้ว เราจะติดต่อกลับเร็วๆ นี้';
        $this->bookingPhone = '';
        $this->bookingDate = '';
        $this->bookingMessage = '';
        $this->showBookingForm = false;
    }

    public function render()
    {
        $isLiked = auth()->check()
            ? WorkLike::where('author_id', auth()->id())->where('work_id', $this->id)->exists()
            : false;

        return view('livewire.frontend.work-detail', compact('isLiked'))
            ->layout('frontend.layout', ['title' => ($this->work->title ?? 'Work') . ' — Chaothuk']);
    }
}
