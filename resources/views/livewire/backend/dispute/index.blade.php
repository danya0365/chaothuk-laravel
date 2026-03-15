<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ศูนย์จัดการข้อพิพาท (Dispute Resolution)</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ดูแลและพิจารณาข้อขัดแย้งระหว่างผู้ว่าจ้างและผู้รับจ้างในระบบ</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800 transition-all">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:w-1/2 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
            </div>
            <input wire:model.live.debounce.500ms="search" type="text" placeholder="ค้นหาเหตุผล หรือชื่อ/อีเมลคู่กรณี..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
        </div>
        
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4">
            <select wire:model.live="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                <option value="all">สถานะทั้งหมด</option>
                <option value="open">🚨 เปิดข้อพิพาทใหม่</option>
                <option value="resolved">✅ ไกล่เกลี่ย/ตัดสินแล้ว</option>
                <option value="closed">🔒 ปิดเคส/ยุติเรื่องแล้ว</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden relative min-h-[400px]">
        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search, status" class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 z-10 flex flex-col items-center justify-center backdrop-blur-sm">
            <svg class="animate-spin h-10 w-10 text-indigo-600 dark:text-indigo-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">กำลังโหลดข้อมูล...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">ประเด็นข้อพิพาท</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">โจทก์ (ผู้ร้อง)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">จำเลย (ผู้ถูกร้อง)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">สถานะ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">เวลาเกิดเหตุ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($disputes as $dispute)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-indigo-600 dark:text-indigo-400 font-bold mb-1 inline-block">
                                    {{ $dispute->reason ?? 'ไม่มีหัวข้อ' }}
                                </span>
                                <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 max-w-sm mt-1">
                                    {{ $dispute->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($dispute->reporter)
                                            <a href="{{ route('backend.users.show', $dispute->reporter_id) }}" class="text-indigo-600 hover:underline dark:text-indigo-400 font-medium" target="_blank">{{ $dispute->reporter->name }}</a>
                                        @else
                                            <span class="text-gray-500 italic">- ไม่ระบุ -</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($dispute->respondent)
                                            <a href="{{ route('backend.users.show', $dispute->respondent_id) }}" class="text-indigo-600 hover:underline dark:text-indigo-400 font-medium" target="_blank">{{ $dispute->respondent->name }}</a>
                                        @else
                                            <span class="text-gray-500 italic">- ไม่ระบุ -</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dispute->status === 'resolved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                        ไกล่เกลี่ยแล้ว
                                    </span>
                                @elseif($dispute->status === 'closed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                        ปิดเรื่อง/ยุติ
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 animate-pulse">
                                        เปิดพิพาทใหม่
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $dispute->created_at->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    
                                    <!-- View Details Button -->
                                    <a href="{{ route('backend.disputes.show', $dispute->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>

                                    <button onclick="confirm('คุณแน่ใจหรือไม่ที่จะลบเอกสารข้อพิพาทนี้อย่างถาวร? การลบอาจส่งผลต่อหลักฐานทางคดี') || event.stopImmediatePropagation()" wire:click="deleteDispute({{ $dispute->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                ไม่พบข้อมูลข้อพิพาทในระบบ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($disputes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                {{ $disputes->links() }}
            </div>
        @endif
    </div>
</div>
