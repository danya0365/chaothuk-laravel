<?php

namespace App\Livewire\Backend\Post;

use App\Models\Post;
use App\Models\UserReport;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.backend')]
#[Title('รายละเอียดโพสต์และจัดการเนื้อหา - Admin')]
class Show extends Component
{
    public $postId;
    
    // Admin Reply Form
    public $replyBody = '';
    public $replyToId = null; 

    // Delete Modal
    public $itemToDelete = null;

    public function mount($post)
    {
        $this->postId = $post;
        $this->replyToId = $post;
    }

    public function setReplyTo($id)
    {
        $this->replyToId = $id;
        $this->dispatch('focus-reply-input');
    }

    public function cancelReplyTo()
    {
        $this->replyToId = $this->postId;
        $this->replyBody = '';
    }

    public function submitAdminReply()
    {
        $this->validate([
            'replyBody' => 'required|string|max:2000'
        ], [
            'replyBody.required' => 'กรุณากรอกข้อความตอบกลับ'
        ]);

        Post::create([
            'content' => $this->replyBody,
            'author_id' => Auth::id(),
            'parent_id' => $this->replyToId,
            'is_suspended' => false,
        ]);

        $this->replyBody = '';
        $this->replyToId = $this->postId;
        session()->flash('success', 'เพิ่มข้อความการตอบกลับในฐานะผู้ดูแลระบบสำเร็จแล้ว');
    }

    public function toggleSuspend($id)
    {
        $item = Post::findOrFail($id);
        $item->is_suspended = !$item->is_suspended;
        $item->save();

        $action = $item->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} สำเร็จ");
    }

    public function confirmDelete($id)
    {
        $this->itemToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->itemToDelete = null;
    }

    public function deleteItem()
    {
        if ($this->itemToDelete) {
            $item = Post::findOrFail($this->itemToDelete);
            
            // Delete its child replies cascade
            $item->replies()->delete();
            $item->delete();
            
            $this->itemToDelete = null;
            session()->flash('success', 'ลบข้อความออกจากระบบสำเร็จ');

            // If we deleted the main post, redirect back
            if ($item->id == $this->postId) {
                return redirect()->route('backend.posts.index');
            }
        }
    }

    public function render()
    {
        // Load the main post with author and context relations
        $post = Post::with(['author', 'works', 'recruits'])
                    ->findOrFail($this->postId);

        // Fetch reports for this specific main post
        $reports = tap(UserReport::where('reportable_type', \App\Models\Post::class)
                            ->where('reportable_id', $this->postId)
                            ->latest()
                            ->get(), function($reports) {
                                $reports->load('reporter');
                            });

        // Load recursive replies (up to 3 levels deep for UI safety).
        $replies = Post::where('parent_id', $this->postId)
            ->with([
                'author', 
                'replies' => function($q) {
                    $q->with([
                        'author', 
                        'replies' => function($q2) {
                            $q2->with('author');
                        }
                    ])->latest();
                }
            ])
            ->get();

        return view('livewire.backend.post.show', [
            'post' => $post,
            'replies' => $replies,
            'reports' => $reports
        ]);
    }
}
