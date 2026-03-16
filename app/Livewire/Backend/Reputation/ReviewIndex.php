<?php

namespace App\Livewire\Backend\Reputation;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserReputationReview;

class ReviewIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $ratingFilter = '';
    public $typeFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRatingFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function deleteReview($id)
    {
        // Simple soft delete for moderation purposes
        $review = UserReputationReview::find($id);
        if ($review) {
            $review->delete();
            session()->flash('success', 'ลบรีวิวเรียบร้อยแล้ว (ซ่อนจากระบบ)');
        }
    }

    public function render()
    {
        $query = UserReputationReview::query()
            ->with(['reviewer', 'reviewee']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('comment', 'like', '%' . $this->search . '%')
                  ->orWhereHas('reviewer', function ($qReviewer) {
                      $qReviewer->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('reviewee', function ($qReviewee) {
                      $qReviewee->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->ratingFilter) {
            // Allows filtering by exact rating (1-5)
            $query->where('overall_rating', $this->ratingFilter);
        }

        if ($this->typeFilter) {
            $query->where('booking_type', $this->typeFilter);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.backend.reputation.review-index', compact('reviews'))
            ->layout('layouts.backend', ['title' => 'ประวัติการรีวิว (Reputation Reviews)']);
    }
}
