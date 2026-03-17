<div class="p-6 sm:p-10 space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ประวัติธุรกรรมระบบ (System Ledger)</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ตรวจสอบพฤติกรรมการเคลื่อนไหวของเครดิตในระบบทั้งหมด</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto flex-1">
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="ค้นหาเลขอ้างอิง, คำอธิบาย...">
                </div>

                <div class="w-full sm:w-48">
                    <select wire:model.live="type" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- ทุกประเภท --</option>
                        <option value="deposit">ฝากเงิน (Deposit)</option>
                        <option value="payment">ชำระเงิน (Payment)</option>
                        <option value="refund">คืนเงิน (Refund)</option>
                        <option value="withdrawal">ถอนเงิน (Withdrawal)</option>
                        <option value="adjustment">ปรับปรุง (Adjustment)</option>
                    </select>
                </div>
            </div>
            @if($user_id)
            <div class="text-sm">
                กำลังกรอง User ID: <strong>{{ $user_id }}</strong>
                <button wire:click="$set('user_id', '')" class="ml-2 text-red-500 hover:text-red-700 text-xs">ล้างตัวกรอง</button>
            </div>
            @endif
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">วันเวลา</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ผู้ใช้งาน</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">รายละเอียด / อ้างอิง</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">จำนวนเครดิต</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ยอดคงเหลือใหม่</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($transactions as $txn)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $txn->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $txn->wallet->user->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">UID: {{ $txn->wallet->user_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                <div class="font-medium">
                                    @php
                                        $typeStr = match($txn->type->value ?? $txn->type) {
                                            'deposit' => 'ฝากเงิน (Deposit)',
                                            'payment' => 'ชำระค่าบริการ (Payment)',
                                            'refund' => 'คืนเงิน (Refund)',
                                            'withdrawal' => 'ถอนเงิน (Withdrawal)',
                                            'adjustment' => 'ปรับปรุงยอด (Adjustment)',
                                            default => $txn->type->value ?? $txn->type
                                        };
                                        $typeColor = match($txn->type->value ?? $txn->type) {
                                            'deposit', 'refund', 'adjustment' => 'text-green-600 dark:text-green-400',
                                            'payment', 'withdrawal' => 'text-red-500 dark:text-red-400',
                                            default => 'text-gray-500'
                                        };
                                    @endphp
                                    <span class="{{ $typeColor }}">{{ $typeStr }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs truncate">
                                    {{ $txn->description ?? '-' }}
                                    @if($txn->reference_id)
                                        <br>Ref: {{ class_basename($txn->reference_type) }} #{{ $txn->reference_id }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sm {{ $txn->amount > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-mono text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format($txn->balance_after, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                ไม่พบรายการธุรกรรม
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
