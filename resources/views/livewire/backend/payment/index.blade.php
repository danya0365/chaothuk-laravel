<div class="p-6 sm:p-10 space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">รายการชำระเงิน (Fiat Payments)</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ตรวจสอบประวัติการเติมเงินจริงผ่าน Payment Gateway</p>
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
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="ค้นหา Transaction ID, ผู้ใช้...">
                </div>

                <div class="w-full sm:w-48">
                    <select wire:model.live="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- ทุกสถานะ --</option>
                        <option value="pending">รอชำระเงิน (Pending)</option>
                        <option value="successful">สำเร็จ (Successful)</option>
                        <option value="failed">ล้มเหลว (Failed)</option>
                        <option value="refunded">คืนเงินแล้ว (Refunded)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">วันเวลา</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gateway Transaction ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">บัญชีผู้ใช้</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">จำนวนเงิน ({{ \App\Models\Payment::first()->currency ?? 'THB' }})</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-mono">
                                {{ $payment->gateway_transaction_id ?? '-' }}
                                <div class="text-xs text-gray-500 mt-1">วิธีชำระ: {{ $payment->payment_method ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $payment->user->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">UID: {{ $payment->user_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusStr = match($payment->status->value ?? $payment->status) {
                                        'pending' => 'รอชำระ',
                                        'successful' => 'สำเร็จ',
                                        'failed' => 'ล้มเหลว',
                                        'refunded' => 'คืนเงินแล้ว',
                                        default => $payment->status->value ?? $payment->status
                                    };
                                    $statusColor = match($payment->status->value ?? $payment->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'successful' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                    {{ $statusStr }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                ไม่พบรายการชำระเงิน
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
