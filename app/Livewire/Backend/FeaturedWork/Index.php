<?php

namespace App\Livewire\Backend\FeaturedWork;

use App\Models\FeaturedWork;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all'; // all, active, expired
    public $paymentMethod = 'all'; // all, wallet, admin_override
    public $featuredWorkIdToRevoke = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'paymentMethod' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethod()
    {
        $this->resetPage();
    }

    public function confirmRevokeFeature($id)
    {
        $this->featuredWorkIdToRevoke = $id;
    }

    public function closeRevokeFeatureModal()
    {
        $this->featuredWorkIdToRevoke = null;
    }

    public function revokeFeature()
    {
        if (!$this->featuredWorkIdToRevoke) return;
        
        $activeFeature = FeaturedWork::findOrFail($this->featuredWorkIdToRevoke);

        if ($activeFeature->end_at >= now()) {
            $activeFeature->update(['end_at' => now()->subSecond()]);
            session()->flash('success', "ยกเลิกการเข้าร่วมรายการแนะนำ (รหัสธุรกรรม #{$activeFeature->id}) ของงานเรียบร้อยแล้ว");
        }
        
        $this->closeRevokeFeatureModal();
    }

    public function render()
    {
        $query = FeaturedWork::with(['work', 'work.author', 'approvedByUser']);

        if (!empty($this->search)) {
            $query->whereHas('work', function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('title', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status === 'active') {
            $query->where('end_at', '>=', now());
        } elseif ($this->status === 'expired') {
            $query->where('end_at', '<', now());
        }

        if ($this->paymentMethod === 'wallet') {
            $query->where('payment_method', 'wallet');
        } elseif ($this->paymentMethod === 'admin_override') {
            $query->where('payment_method', 'admin_override');
        }

        $featuredWorks = $query->orderBy('id', 'desc')->paginate(20);

        return view('livewire.backend.featured-work.index', [
            'featuredWorks' => $featuredWorks,
        ])->layout('layouts.backend', [
            'title' => 'รายการโปรโมท (Featured Works)',
            'pageTitle' => 'จัดการรายการโปรโมทงานเช่า',
        ]);
    }
}
