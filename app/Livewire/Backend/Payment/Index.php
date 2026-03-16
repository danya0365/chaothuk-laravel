<?php

namespace App\Livewire\Backend\Payment;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('รายการชำระเงิน (Fiat Payments) - Admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

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
        $payments = Payment::query()
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where('gateway_transaction_id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(20);

        return view('livewire.backend.payment.index', [
            'payments' => $payments,
        ]);
    }
}
