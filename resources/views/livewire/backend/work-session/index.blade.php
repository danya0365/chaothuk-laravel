<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 text-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ระบบติดตามเซสชันงาน (Work Sessions)</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">ตรวจสอบและจัดการเซสชันการทำงานที่มีอยู่ในระบบ</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="search" class="sr-only">ค้นหา</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" id="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" placeholder="ค้นหาตามชื่อ/อีเมลของ Worker หรือ Customer...">
                </div>
            </div>
            
            <div>
                <label for="statusFilter" class="sr-only">กรองสถานะ</label>
                <select wire:model.live="statusFilter" id="statusFilter" class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">-- กรองสถานะทั้งหมด --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ __('common.work_session_status-' . $status) ?? $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">เซสชัน / เวลา</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ผู้ให้บริการ (Worker)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ลูกค้า (Customer)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ราคา/สถานะ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="font-medium text-indigo-600 dark:text-indigo-400">
                                    #WS-{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    เริ่ม: {{ $session->started_at ? $session->started_at->format('d/m/Y H:i') : '-' }}<br>
                                    สิ้นสุด: {{ $session->ended_at ? $session->ended_at->format('d/m/Y H:i') : '-' }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                @if($session->worker)
                                <div class="flex items-center space-x-3">
                                    <x-backend.avatar :src="$session->worker->getAvatar()" :name="$session->worker->name" size="h-8 w-8" />
                                    <div>
                                        <a href="{{ route('backend.users.show', $session->worker->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $session->worker->name }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $session->worker->email }}</div>
                                    </div>
                                </div>
                                @else
                                    <span class="text-gray-500 italic">Deleted User</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                @if($session->customer)
                                <div class="flex items-center space-x-3">
                                    <x-backend.avatar :src="$session->customer->getAvatar()" :name="$session->customer->name" size="h-8 w-8" />
                                    <div>
                                        <a href="{{ route('backend.users.show', $session->customer->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $session->customer->name }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $session->customer->email }}</div>
                                    </div>
                                </div>
                                @else
                                    <span class="text-gray-500 italic">Deleted User</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="font-medium">฿{{ number_format($session->price_agreed, 2) }}</div>
                                <div class="mt-1">
                                    @php
                                        $statusClass = match($session->status) {
                                            'active', 'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border-green-200 dark:border-green-800',
                                            'cancelled', 'aborted' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border-red-200 dark:border-red-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-700',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClass }}">
                                        {{ __('common.work_session_status-' . $session->status) ?? $session->status }}
                                    </span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('backend.logs.work-sessions.show', $session->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                ไม่พบข้อมูลเซสชันงาน
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sessions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
