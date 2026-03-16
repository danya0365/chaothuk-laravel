<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ควบคุมเนื้อหาสาธารณะ (Content Moderation)</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ตรวจสอบความเรียบร้อยของรีวิว โพสต์ชุมชน และประกาศตามหาเช่า</p>
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

    <!-- Content Type Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button wire:click="$set('type', 'post')" class="{{ $type === 'post' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                รีวิว & ชุมชน (Posts)
            </button>
            <button wire:click="$set('type', 'recruit')" class="{{ $type === 'recruit' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                ประกาศรับสมัคร/ตามหาเช่า (Recruits)
            </button>
        </nav>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:w-1/2 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
            </div>
            <input wire:model.live.debounce.500ms="search" type="text" placeholder="ค้นหาเนื้อหา {{ $type === 'post' ? 'โพสต์/รีวิว' : 'ประกาศ' }}..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition">
        </div>
        
        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-4">
            <select wire:model.live="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                <option value="all">สถานะทั้งหมด</option>
                <option value="active">🟢 แสดงผลปกติ</option>
                <option value="suspended">🔴 ถูกระงับ</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden relative min-h-[400px]">
        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search, status, type, toggleSuspend" class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 z-10 flex flex-col items-center justify-center backdrop-blur-sm">
            <svg class="animate-spin h-10 w-10 text-indigo-600 dark:text-indigo-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">กำลังโหลดข้อมูล...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">เนื้อหา (Content)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">ลักษณะ/รายละเอียด</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">ผู้เขียน</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">สถานะ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">เมื่อ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                @if($type === 'post')
                                    <div class="text-sm text-gray-900 dark:text-white line-clamp-2 max-w-md">
                                        {{ $item->title ? $item->title . ' - ' : '' }}{{ $item->content }}
                                    </div>
                                    @if($item->rating)
                                        <div class="flex items-center mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="h-3 w-3 {{ $i <= $item->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    @endif
                                @else
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1 max-w-md">
                                        {{ $item->title }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-1 max-w-md">
                                        {{ $item->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @if($type === 'post')
                                    @if($item->works && $item->works->count() > 0)
                                        รีวิวงานเช่า
                                    @elseif($item->recruits && $item->recruits->count() > 0)
                                        รีวิวประกาศรับสมัคร
                                    @elseif($item->parent_id)
                                        คอมเมนต์ตอบกลับ
                                    @else
                                        โพสต์ทั่วไป
                                    @endif
                                @else
                                    <div class="font-medium text-green-600 dark:text-green-400">งบ: ฿{{ number_format($item->budget) }}</div>
                                    <div class="text-xs mt-1">{{ optional($item->categories->first())->name ?? 'ทั่วไป' }}</div>
                                    <div class="text-xs mt-0.5">{{ $item->recruit_status === 'open' ? '🟢 เปิดรับ' : '🔴 ปิดรับ' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <a href="{{ route('backend.users.show', $item->author_id) }}" class="hover:underline text-indigo-600 dark:text-indigo-400" target="_blank">{{ optional($item->author)->name ?? 'Unknown user' }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->is_suspended)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                        ถูกระงับ
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                        ปกติ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    
                                    <!-- Suspend Button -->
                                    <button wire:click="toggleSuspend({{ $item->id }})" class="{{ $item->is_suspended ? 'text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400' : 'text-red-500 hover:text-red-700 dark:hover:text-red-400' }}" title="{{ $item->is_suspended ? 'ยกเลิกการระงับ' : 'ระงับการแสดงผล' }}">
                                        @if($item->is_suspended)
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        @endif
                                    </button>

                                    <!-- View Details (to completely inspect) -->
                                    <a href="{{ route('backend.posts.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors" title="ดูรายละเอียดแบบเจาะลึก">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>

                                    <!-- Delete -->
                                    <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                ไม่พบรายการเนื้อหา
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($itemIdToDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="cancelDelete"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ลบเนื้อหา</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                คุณแน่ใจหรือไม่ที่จะลบเนื้อหานี้? (เนื้อหาเบื้องต้นจะถูกเก็บในถังขยะ: Soft Delete)
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteItem" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">ยืนยันการลบ</button>
                        <button wire:click="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
