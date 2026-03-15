<?php

namespace App\Livewire\Backend\Post;

use App\Models\Post;
use App\Models\Recruit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการเนื้อหา โพสต์และประกาศ (Content Moderation)')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, active, suspended
    public $type = 'post'; // post, recruit
    
    public $itemIdToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'type' => ['except' => 'post'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
        $this->search = '';
        $this->status = 'all';
    }

    public function toggleSuspend($id)
    {
        if ($this->type === 'post') {
            $item = Post::findOrFail($id);
        } else {
            $item = Recruit::findOrFail($id);
        }
        
        $item->is_suspended = !$item->is_suspended;
        $item->save();

        $action = $item->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        $label = $this->type === 'post' ? 'โพสต์/รีวิว' : 'ประกาศรับสมัคร';
        session()->flash('success', "{$action} {$label} สำเร็จ");
    }

    public function confirmDelete($id)
    {
        $this->itemIdToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->itemIdToDelete = null;
    }

    public function deleteItem()
    {
        if ($this->itemIdToDelete) {
            if ($this->type === 'post') {
                $item = Post::findOrFail($this->itemIdToDelete);
                // Also delete replies if it's a parent post
                $item->replies()->delete();
                $item->delete();
                $label = 'โพสต์รีวิว/ชุมชน';
            } else {
                $item = Recruit::findOrFail($this->itemIdToDelete);
                $item->delete();
                $label = 'ประกาศรับสมัคร (Recruit)';
            }
            
            $this->itemIdToDelete = null;
            session()->flash('success', "ลบ {$label} ออกจากระบบสำเร็จ");
        }
    }

    public function render()
    {
        if ($this->type === 'post') {
            $query = Post::with(['author', 'parent', 'works', 'recruits']);

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            }
        } else {
            $query = Recruit::with(['author', 'categories']);

            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }
        }

        if ($this->status === 'active') {
             $query->where('is_suspended', false);
        } elseif ($this->status === 'suspended') {
             $query->where('is_suspended', true);
        }

        $items = $query->latest()->paginate(10);

        return view('livewire.backend.post.index', [
            'items' => $items,
        ]);
    }
}
