<?php

namespace App\Livewire\Backend\Wallet;

use App\Models\WalletTransaction;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.backend')]
#[Title('ประวัติธุรกรรม (Wallet Transactions) - Admin')]
class Transactions extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $user_id = '';

    public function mount()
    {
        $this->user_id = request()->query('search', '');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $transactions = WalletTransaction::query()
            ->with(['wallet.user', 'reference'])
            ->when($this->search, function ($query) {
                $query->where('reference_id', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->user_id, function ($query) {
                $query->whereHas('wallet', function ($q) {
                    $q->where('user_id', $this->user_id);
                });
            })
            ->latest()
            ->paginate(20);

        return view('livewire.backend.wallet.transactions', [
            'transactions' => $transactions,
        ]);
    }
}
