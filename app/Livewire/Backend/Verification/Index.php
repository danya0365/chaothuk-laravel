<?php

namespace App\Livewire\Backend\Verification;

use App\Models\UserVerification;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('จัดการการยืนยันตัวตน (KYC) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'pending'; // Filter by status, default to pending

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'pending'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = UserVerification::with('user')
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', function ($q) {
                $q->where('status', $this->status);
            })
            ->latest('created_at');

        return view('livewire.backend.verification.index', [
            'verifications' => $query->paginate(10)
        ]);
    }
}
