<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Models\Recruit;
use App\Models\RecruitBooking;
use App\Models\RecruitReview;
use Livewire\Component;

class RecruitDetail extends Component
{
    public int $id;
    public ?Recruit $recruit = null;
    public ?array $reviews = null;

    // Review form
    public bool $showReviewForm = false;
    public int $reviewRating = 5;
    public string $reviewContent = '';
    public ?string $reviewMessage = null;

    // Apply (booking) form
    public bool $showApplyForm = false;
    public string $applyPhone = '';
    public string $applyDate = '';
    public string $applyMessage = '';
    public ?string $applySuccess = null;

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->recruit = Recruit::with(['author', 'province', 'workType', 'categories'])->findOrFail($id);
        $this->loadReviews();
    }

    public function loadReviews(): void
    {
        $postIds = \DB::table('recruits_reviews')->where('recruit_id', $this->id)->pluck('post_id');
        $this->reviews = Post::with(['author'])
            ->whereIn('id', $postIds)
            ->whereNull('parent_id')
            ->latest()
            ->get()->toArray();
    }

    public function submitReview(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }
        $this->validate(['reviewContent' => 'required|min:5', 'reviewRating' => 'required|integer|min:1|max:5']);

        $post = Post::create([
            'author_id' => auth()->id(),
            'content'   => $this->reviewContent,
            'rating'    => $this->reviewRating,
            'images'    => json_encode([]),
        ]);
        RecruitReview::create(['recruit_id' => $this->id, 'post_id' => $post->id]);

        $this->reviewMessage = '✅ รีวิวของคุณถูกบันทึกแล้ว';
        $this->reviewContent = '';
        $this->showReviewForm = false;
        $this->loadReviews();
    }

    public function submitApply(): void
    {
        if (!auth()->check()) { $this->redirect(route('login')); return; }
        $this->validate(['applyPhone' => 'required|min:9', 'applyDate' => 'required|date']);

        RecruitBooking::create([
            'author_id'        => auth()->id(),
            'recruit_id'       => $this->id,
            'mobile_phone'     => $this->applyPhone,
            'booking_date'     => $this->applyDate,
            'customer_message' => $this->applyMessage,
            'booking_status'   => 'waiting-to-confirm',
        ]);

        $this->applySuccess = '✅ ส่งใบสมัครแล้ว เราจะติดต่อกลับเร็วๆ นี้';
        $this->applyPhone = $this->applyDate = $this->applyMessage = '';
        $this->showApplyForm = false;
    }

    public function render()
    {
        return view('livewire.frontend.recruit-detail')
            ->layout('frontend.layout', ['title' => ($this->recruit->title ?? 'Recruit') . ' — Chaothuk']);
    }
}
