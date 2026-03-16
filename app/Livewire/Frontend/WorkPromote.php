<?php

namespace App\Livewire\Frontend;

use App\Enums\WalletTransactionType;
use App\Models\FeaturedWork;
use App\Models\WalletTransaction;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WorkPromote extends Component
{
    public $work;
    public $selectedPackage = 1; // Default to 1 day
    public $packages = [
        1 => ['days' => 1, 'price' => 10],
        3 => ['days' => 3, 'price' => 25],
        7 => ['days' => 7, 'price' => 50],
        30 => ['days' => 30, 'price' => 200],
    ];

    public $errorMessage = null;

    public function mount(Work $work)
    {
        // Ensure the logged-in user is the author
        if ($work->author_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->work = $work;
    }

    public function promote()
    {
        $this->errorMessage = null;
        $user = Auth::user();
        $user->load('wallet');

        if (!isset($this->packages[$this->selectedPackage])) {
            $this->errorMessage = 'ข้อมูลแพ็กเกจไม่ถูกต้อง';
            return;
        }

        $package = $this->packages[$this->selectedPackage];
        $price = $package['price'];
        $days = $package['days'];

        // Check if user has a wallet and sufficient balance
        if (!$user->wallet || $user->getWalletBalance() < $price) {
            $this->errorMessage = 'ยอดเครดิตในกระเป๋าเงินของคุณไม่พอ กรุณาเติมเงินก่อนทำรายการ';
            return;
        }

        $success = false;
        DB::beginTransaction();
        try {
            // Deduct from User Wallet
            $wallet = $user->wallet;
            $newBalance = $wallet->balance - $price;
            $wallet->update(['balance' => $newBalance]);

            // Create Immutable Ledger Record
            $transaction = WalletTransaction::create([
                'user_wallet_id' => $wallet->id,
                'type' => WalletTransactionType::PAYMENT,
                'amount' => -$price,
                'balance_after' => $newBalance,
                'description' => "ดันโฟสต์/ฟีเจอร์ โปรโมทงาน ({$days} วัน)",
            ]);

            // Determine Start and End dates for Renewal
            $activeFeature = $this->work->activeFeature;
            $startAt = $activeFeature ? $activeFeature->end_at : now();
            $endAt = $startAt->copy()->addDays($days);

            // Create Featured Work Entry
            $featured = FeaturedWork::create([
                'work_id' => $this->work->id,
                'author_id' => $user->id,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'amount_paid' => $price,
                'payment_method' => 'wallet',
                'payment_status' => 'paid',
                'is_approved' => true,
            ]);

            // Link the transaction reference
            $transaction->reference()->associate($featured);
            $transaction->save();

            DB::commit();
            $success = true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Work promote error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->errorMessage = 'เกิดข้อผิดพลาดในการทำรายการ กรุณาลองใหม่อีกครั้ง';
        }

        if ($success) {
            session()->flash('success', 'งานของคุณถูกดันเป็นฟีเจอร์เรียบร้อยแล้ว!');
            return $this->redirectRoute('frontend.works.show', ['id' => $this->work->id], navigate: true);
        }
    }

    public function render()
    {
        $walletBalance = Auth::user()->getWalletBalance();

        return view('livewire.frontend.work-promote', [
            'walletBalance' => $walletBalance
        ])->layout('frontend.layout', ['title' => 'โปรโมทงาน — Chaothuk']);
    }
}
