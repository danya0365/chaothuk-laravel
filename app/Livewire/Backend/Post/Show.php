<?php

namespace App\Livewire\Backend\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('รายละเอียดโพสต์/รีวิว - Admin')]
class Show extends Component
{
    public Post $post;
    public $replies = [];

    public function mount(Post $post)
    {
        $this->post = $post->load(['author', 'works', 'recruits']);
        $this->replies = $this->post->replies()->with('author')->latest()->get();
    }

    public function toggleSuspend()
    {
        $this->post->is_suspended = !$this->post->is_suspended;
        $this->post->save();

        $action = $this->post->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} สำเร็จ");
    }

    public function render()
    {
        return view('livewire.backend.post.show');
    }
}
