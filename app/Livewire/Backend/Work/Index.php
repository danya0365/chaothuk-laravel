<?php

namespace App\Livewire\Backend\Work;

use App\Models\Work;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการงานเช่า (Works) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, active, suspended
    public $category_id = 'all';
    
    public $workIdToDelete = null;

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
        $work = Work::findOrFail($id);
        // Toggle display_priority between 0 and 100 for featuring
        $work->display_priority = $work->display_priority > 0 ? 0 : 100;
        $work->save();

        $action = $work->display_priority > 0 ? 'แนะนำ' : 'เลิกแนะนำ';
        session()->flash('success', "ตั้งค่าให้งาน {$work->code} เป็นรายการ{$action} แล้ว");
    }

    public function toggleSuspend($id)
    {
        $work = Work::findOrFail($id);
        $work->is_suspended = !$work->is_suspended;
        $work->save();

        $action = $work->is_suspended ? 'ระงับการแสดงผล' : 'ยกเลิกการระงับ';
        session()->flash('success', "{$action} งานรหัส {$work->code} สำเร็จ");
    }

    public function confirmDelete($id)
    {
        $this->workIdToDelete = $id;
    }

    public function cancelDelete()
    {
        $this->workIdToDelete = null;
    }

    public function deleteWork()
    {
        if ($this->workIdToDelete) {
            $work = Work::findOrFail($this->workIdToDelete);
            $work->delete();
            $this->workIdToDelete = null;
            session()->flash('success', 'ลบงานเช่าออกจากระบบสำเร็จ');
        }
    }

    public function render()
    {
        $query = Work::with(['author', 'categories']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
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

        $works = $query->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('livewire.backend.work.index', [
            'works' => $works,
            'categories' => $categories,
        ]);
    }
}
