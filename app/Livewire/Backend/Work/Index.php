<?php

namespace App\Livewire\Backend\Work;

use App\Models\Work;
use App\Models\Category;
use App\Models\FeaturedWork;
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
    public $workIdToFeature = null;
    public $featureDays = 7;
    public $featureNote = '';
    public $isAppendingFeature = false;
    public $workIdToRevokeFeature = null;
    
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

    public function openFeatureModal($id)
    {
        $this->workIdToFeature = $id;
        $work = Work::findOrFail($id);
        
        $activeFeature = FeaturedWork::where('work_id', $work->id)
            ->where('is_approved', true)
            ->where('end_at', '>=', now())
            ->first();

        if ($activeFeature) {
            $this->isAppendingFeature = true;
            $this->featureDays = 7; // Default to append 7 days
        } else {
            $this->isAppendingFeature = false;
            $this->featureDays = 7;
        }
        $this->featureNote = '';
    }

    public function closeFeatureModal()
    {
        $this->workIdToFeature = null;
        $this->reset(['featureDays', 'featureNote', 'isAppendingFeature']);
    }

    public function saveFeature()
    {
        $this->validate([
            'featureDays' => 'required|integer|min:1|max:365',
            'featureNote' => 'nullable|string|max:255',
        ]);

        $work = Work::findOrFail($this->workIdToFeature);
        
        $activeFeature = FeaturedWork::where('work_id', $work->id)
            ->where('is_approved', true)
            ->where('end_at', '>=', now())
            ->first();

        if ($activeFeature) {
            // Append to existing
            $activeFeature->update([
                'end_at' => \Carbon\Carbon::parse($activeFeature->end_at)->addDays($this->featureDays),
                // Optionally log the note somewhere, or we can just append it to a field if we add one in the future.
            ]);
            $action = 'ขยายเวลาแนะนำเพิ่ม ' . $this->featureDays . ' วัน';
        } else {
            // Create new
            FeaturedWork::create([
                'work_id' => $work->id,
                'author_id' => $work->author_id,
                'start_at' => now(),
                'end_at' => now()->addDays($this->featureDays),
                'amount_paid' => 0,
                'payment_method' => 'admin_override',
                'payment_status' => 'paid',
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'slot_position' => 0,
            ]);
            $action = 'ตั้งเป็นรายการแนะนำ ' . $this->featureDays . ' วัน';
        }

        $this->closeFeatureModal();
        session()->flash('success', "{$action} ให้งาน {$work->code} แล้ว");
    }

    public function confirmRevokeFeature($id)
    {
        $this->workIdToRevokeFeature = $id;
    }

    public function closeRevokeFeatureModal()
    {
        $this->workIdToRevokeFeature = null;
    }

    public function revokeFeature()
    {
        if (!$this->workIdToRevokeFeature) return;

        $work = Work::findOrFail($this->workIdToRevokeFeature);
        $activeFeature = FeaturedWork::where('work_id', $work->id)
            ->where('is_approved', true)
            ->where('end_at', '>=', now())
            ->first();

        if ($activeFeature) {
            $activeFeature->update(['end_at' => now()->subSecond()]);
            session()->flash('success', "ยกเลิกการเข้าร่วมรายการแนะนำของงาน {$work->code} แล้ว");
        }

        $this->closeRevokeFeatureModal();
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
        $query = Work::with(['author', 'categories', 'activeFeature']);

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

        $works = $query->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('livewire.backend.work.index', [
            'works' => $works,
            'categories' => $categories,
        ]);
    }
}
