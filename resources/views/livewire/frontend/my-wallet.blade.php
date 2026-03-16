<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-white">💳 กระเป๋าเงินของฉัน</h1>
    </div>

    <!-- Balance Card -->
    <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-6 shadow-lg mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-40 h-40 rounded-full bg-white opacity-10"></div>
        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <p class="text-white/80 text-sm font-medium mb-1">ยอดเงินคงเหลือ (เครดิต)</p>
                <div class="text-4xl font-black text-white flex items-baseline gap-2">
                    <span>฿</span>
                    <span>{{ number_format($balance, 2) }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                @if(app()->environment('production'))
                    {{-- Production / Disabled Placeholder --}}
                    <button class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-6 rounded-full transition border border-white/30 shadow-sm backdrop-blur-sm cursor-not-allowed opacity-50" title="ระบบเติมเงินกำลังอยู่ระหว่างการพัฒนา">
                        + เติมเงิน
                    </button>
                @else
                    {{-- Local Testing Bypass --}}
                    <button wire:click="testDeposit(100)" class="bg-white hover:bg-white/90 text-orange-600 font-bold py-2 px-4 rounded-full transition shadow-sm drop-shadow">
                        + เทสเติม 100
                    </button>
                    <button wire:click="testDeposit(500)" class="bg-white hover:bg-white/90 text-orange-600 font-bold py-2 px-4 rounded-full transition shadow-sm drop-shadow">
                        + เทสเติม 500
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-6 flex items-start gap-3">
            <span class="text-green-400 text-lg mt-0.5">✅</span>
            <div>
                <p class="text-sm text-green-400 mt-1 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 flex items-start gap-3">
            <span class="text-red-400 text-lg mt-0.5">⚠️</span>
            <div>
                <p class="text-sm text-red-400 mt-1 font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Transactions List -->
    <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-white">ประวัติรายการ (Transactions)</h2>
        </div>

        @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->count() > 0)
            <div class="space-y-3">
                @foreach($transactions as $txn)
                    @php
                        $isPositive = $txn->amount > 0;
                        $amountColor = $isPositive ? 'text-green-400' : 'text-red-400';
                        $sign = $isPositive ? '+' : '';
                        
                        $iconStr = match($txn->type->value ?? $txn->type) {
                            'deposit' => '💰',
                            'payment' => '💸',
                            'refund' => '🔄',
                            'withdrawal' => '🏦',
                            'adjustment' => '⚙️',
                            default => '📝'
                        };
                        
                        $typeStr = match($txn->type->value ?? $txn->type) {
                            'deposit' => 'เติมเงิน',
                            'payment' => 'ชำระค่าบริการ',
                            'refund' => 'รับเงินคืน',
                            'withdrawal' => 'ถอนเงิน',
                            'adjustment' => 'ปรับปรุงยอด',
                            default => ucfirst($txn->type->value ?? $txn->type)
                        };
                    @endphp
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-800/50 border border-gray-700 hover:bg-gray-800 shadow-sm transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-xl shrink-0">
                                {{ $iconStr }}
                            </div>
                            <div>
                                <p class="font-semibold text-white text-sm">{{ $typeStr }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $txn->description ?? 'ทำรายการ' }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ $txn->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $amountColor }} text-lg">{{ $sign }}{{ number_format($txn->amount, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $transactions->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-5xl mb-4">💸</div>
                <h3 class="text-white font-medium mb-1">ยังไม่มีประวัติทำรายการ</h3>
                <p class="text-gray-500 text-sm">เมื่อมีการเติมเงินหรือใช้จ่าย จะแสดงรายการขึ้นที่นี่</p>
            </div>
        @endif
    </div>
</div>
