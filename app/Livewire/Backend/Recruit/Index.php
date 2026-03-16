<?php

namespace App\Livewire\Backend\Recruit;

use App\Models\Recruit;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการประกาศจ้างงาน (Recruits) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, active, suspended
    public $category_id = 'all';
    
    public $recruitIdToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'category_id' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function toggleFeature($id)
    {
        $recruit = Recruit::findOrFail($id);
        // Toggle display_priority between 0 and 100 for featuring
        $recruit->display_priority = $recruit->display_priority > 0 ? 0 : 100;
        $recruit->save();

        $action = $recruit->display_priority > 0 ? 'แนะนำ' : 'เลิกแนะนำ';
        session()->flash('success', "ตั้งค่าให้ประกาศ {$recruit->title} เป็นรายการ{$action} แล้ว");
    }

    public function toggleSuspend($id)
    {
        $recruit = Recruit::findOrFail($id);
        $recruit->is_suspended = !$recruit->is_suspended;
        $recruit->save();

        $action = $recruit->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} ประกาศ {$recruit->title} สำเร็จ");
    }

    public function confirmDelete($id)
    {
        $this->recruitIdToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->recruitIdToDelete = null;
    }

    public function deleteRecruit()
    {
        if ($this->recruitIdToDelete) {
            $recruit = Recruit::findOrFail($this->recruitIdToDelete);
            $recruit->delete();
            $this->recruitIdToDelete = null;
            session()->flash('success', 'ลบประกาศจ้างงานออกจากระบบสำเร็จ');
        }
    }

    public function render()
    {
        $query = Recruit::with(['author', 'categories']);

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->status === 'active') {
            $query->where('is_suspended', false);
        } elseif ($this->status === 'suspended') {
            $query->where('is_suspended', true);
        }

        if ($this->category_id !== 'all') {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->category_id);
            });
        }

        $recruits = $query->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('livewire.backend.recruit.index', [
            'recruits' => $recruits,
            'categories' => $categories,
        ]);
    }
}
