<?php

namespace App\Livewire\Frontend;

use App\Models\WalletTransaction;
use App\Enums\WalletTransactionType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MyWallet extends Component
{
    use WithPagination;

    public function testDeposit($amount = 500)
    {
        // Only allow this in local environment for testing
        if (app()->environment('production')) {
            abort(403, 'This feature is only available in local testing.');
        }

        $user = Auth::user();
        if (!$user) return;

        DB::beginTransaction();
        try {
            // Ensure wallet exists
            $wallet = $user->wallet()->firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'status' => 'active']
            );

            $newBalance = $wallet->balance + $amount;
            
            // Record Transaction
            WalletTransaction::create([
                'user_wallet_id' => $wallet->id,
                'type' => WalletTransactionType::DEPOSIT,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'description' => "Test Deposit (Bypass Payment Gateway)",
            ]);

            // Update Wallet Balance
            $wallet->update(['balance' => $newBalance]);

            DB::commit();
            session()->flash('success', "เติมเงินทดสอบสำเร็จจำนวน {$amount} เครดิต");
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "เกิดข้อผิดพลาด: " . $e->getMessage());
        }
    }

    public function render()
    {
        $user = Auth::user();
        $user->load('wallet');
        
        $balance = $user->getWalletBalance();
        
        $transactions = [];
        if ($user->wallet) {
            $transactions = WalletTransaction::where('user_wallet_id', $user->wallet->id)
                ->with('reference')
                ->latest()
                ->paginate(15);
        }

        return view('livewire.frontend.my-wallet', [
            'balance' => $balance,
            'transactions' => $transactions,
        ])->layout('frontend.layout', ['title' => 'กระเป๋าเงินของฉัน — Chaothuk']);
    }
}
