<?php

namespace App\Livewire\Backend\Banner;

use App\Models\Banner;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.backend')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        session()->flash('success', 'ลบแบนเนอร์เรียบร้อยแล้ว');
    }

    public function toggleStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_public = !$banner->is_public;
        $banner->save();
        
        session()->flash('success', 'อัปเดตสถานะการแสดงผลแบนเนอร์เรียบร้อยแล้ว');
    }

    public function togglePinned($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_pinned = !$banner->is_pinned;
        $banner->save();
        
        session()->flash('success', 'อัปเดตสถานะการปักหมุดเรียบร้อยแล้ว');
    }

    public function render()
    {
        $query = Banner::query()->orderByDesc('is_pinned')->orderByDesc('created_at');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('external_url', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterType)) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterStatus !== '') {
            $query->where('is_public', $this->filterStatus === 'public');
        }

        $banners = $query->paginate(20);

        return view('livewire.backend.banner.index', compact('banners'));
    }
}
