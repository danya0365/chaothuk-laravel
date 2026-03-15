<?php

namespace App\Livewire\Backend\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการรีวิว/ชุมชน (Posts) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, active, suspended
    
    public $postIdToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function toggleSuspend($id)
    {
        $post = Post::findOrFail($id);
        $post->is_suspended = !$post->is_suspended;
        $post->save();

        $action = $post->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} โพสต์/รีวิว สำเร็จ");
    }

    public function confirmDelete($id)
    {
        $this->postIdToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->postIdToDelete = null;
    }

    public function deletePost()
    {
        if ($this->postIdToDelete) {
            $post = Post::findOrFail($this->postIdToDelete);
            // Also delete replies if it's a parent post
            $post->replies()->delete();
            $post->delete();
            $this->postIdToDelete = null;
            session()->flash('success', 'ลบโพสต์ออกจากระบบสำเร็จ');
        }
    }

    public function render()
    {
        $query = Post::with(['author', 'parent', 'works', 'recruits']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status === 'active') {
             $query->where('is_suspended', false);
        } elseif ($this->status === 'suspended') {
             $query->where('is_suspended', true);
        }

        $posts = $query->latest()->paginate(15);

        return view('livewire.backend.post.index', [
            'posts' => $posts,
        ]);
    }
}
