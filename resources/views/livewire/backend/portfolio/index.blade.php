<div x-data="{ itemToDelete: null, isDeleteModalOpen: false }">
    <x-slot name="title">แฟ้มผลงาน (Portfolios)</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">แฟ้มผลงาน (Portfolios)</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">ตรวจสอบแฟ้มผลงานที่ถูกสร้างโดยผู้ให้บริการ</p>
        </div>
    </div>

    <!-- Search Box -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="ค้นหาตามชื่อผลงาน หรือ ผู้ให้บริการ...">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">ภาพตัวอย่าง</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ชื่อผลงาน / รายละเอียด</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ผู้ให้บริการ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">อัปเดตเมื่อ</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($portfolios as $portfolio)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $portfolio->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(is_array($portfolio->images) && count($portfolio->images) > 0)
                                    <div class="h-12 w-16 flex-shrink-0 relative">
                                        <img class="h-12 w-16 rounded object-cover border border-gray-200 dark:border-gray-600" src="{{ $portfolio->images[0] }}" alt="">
                                        @if(count($portfolio->images) > 1)
                                            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full">
                                                +{{ count($portfolio->images) - 1 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="h-12 w-16 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-xs">
                                        ไม่มีรูป
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                    {{ $portfolio->title }}
                                </div>
                                <div class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mb-1">
                                    {{ $portfolio->workType?->title ?? 'ไม่มีประเภท' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 max-w-xs">
                                    {{ $portfolio->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <x-backend.avatar :user="$portfolio->user" size="sm" class="mr-3" />
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $portfolio->user?->name ?? 'ไม่ระบุ' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            UID: {{ $portfolio->user_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $portfolio->updated_at?->diffForHumans() ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end">
                                    <button 
                                        type="button"
                                        @click="itemToDelete = {{ $portfolio->id }}; isDeleteModalOpen = true"
                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                    >
                                        <svg class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        ลบ (ระงับ)
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span>ไม่พบข้อมูลแฟ้มผลงาน</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($portfolios->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $portfolios->links() }}
            </div>
        @endif
    </div>

    <!-- Alpine.js Delete Confirmation Modal -->
    <div x-show="isDeleteModalOpen" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="isDeleteModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900/75 transition-opacity" 
                 @click="isDeleteModalOpen = false" 
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="isDeleteModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ยืนยันการลบแฟ้มผลงาน</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <p>คุณแน่ใจหรือไม่ว่าต้องการลบแฟ้มผลงานนี้? การกระทำนี้ไม่สามารถย้อนกลับได้</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            @click="$wire.delete(itemToDelete); isDeleteModalOpen = false" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        ยืนยันการลบ
                    </button>
                    <button type="button" 
                            @click="isDeleteModalOpen = false" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
