<div x-data="{ itemToDelete: null, isDeleteModalOpen: false }">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Header & Action -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">จัดการตราสัญลักษณ์ (Badges Management)</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    ตราสัญลักษณ์ที่ครอบครอง: <span class="font-bold text-gray-900 dark:text-white">{{ $badges->count() }}</span> รายการ
                </p>
            </div>
            <div>
                <button wire:click="openModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    มอบตราสัญลักษณ์
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-400 p-4 m-4">
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Badges Grid -->
        <div class="p-6">
            @if($badges->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($badges as $badge)
                        <div class="relative bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between group">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center border-2 border-amber-200 dark:border-amber-800">
                                    <span class="text-xl">🌟</span>
                                </div>
                                <div>
                                    <h4 class="text-md font-bold text-gray-900 dark:text-white">{{ $badge->label }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ระดับ: เลเวล {{ $badge->badge_level }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">มอบเมื่อ: {{ $badge->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            
                            <!-- Delete Button (appears on hover) -->
                            <button type="button" @click="itemToDelete = {{ $badge->id }}; isDeleteModalOpen = true" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 dark:hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">ไม่มีตราสัญลักษณ์</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ผู้ใช้นี้ยังไม่ได้รับตราสัญลักษณ์ใดๆ</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Assign Badge Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data x-init="document.body.classList.add('overflow-hidden')" @destroyed="document.body.classList.remove('overflow-hidden')">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    มอบตราสัญลักษณ์ (Assign Badge)
                                </h3>
                                <div class="mt-4 space-y-4 text-left">
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">เลือกตราสัญลักษณ์</label>
                                        <select wire:model="selectedBadge" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">-- โปรดเลือก --</option>
                                            @foreach($availableBadges as $case)
                                                {{-- Determine the label --}}
                                                @php
                                                    $label = match ($case->value) {
                                                        'fast_responder' => '⚡ ตอบเร็ว',
                                                        'on_time_king'   => '⏰ ตรงเวลา',
                                                        'five_star'      => '⭐ 5 ดาว',
                                                        'top_earner'     => '💰 งานเยอะ',
                                                        'verified_pro'   => '✅ ยืนยันแล้ว',
                                                        'repeat_magnet'  => '🔁 ลูกค้าประจำ',
                                                        'zero_cancel'    => '🎯 ไม่เคยยกเลิก',
                                                        'community_hero' => '🦸 ฮีโร่ชุมชน',
                                                        default          => $case->value,
                                                    };
                                                @endphp
                                                <option value="{{ $case->value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedBadge') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ระดับเลเวล</label>
                                        <input wire:model="badgeLevel" type="number" min="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="1">
                                        <p class="text-xs text-gray-500 mt-1">หากผู้ใช้มีตราสัญลักษณ์นี้อยู่แล้ว จะเป็นการอัปเดตเลเวลแทน</p>
                                        @error('badgeLevel') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="assignBadge" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm transition">
                            ยืนยัน
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif

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
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ยืนยันการเพิกถอนตราสัญลักษณ์</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <p>คุณแน่ใจหรือไม่ที่จะเพิกถอนตราสัญลักษณ์นี้ออกจากผู้ใช้? การกระทำนี้ไม่สามารถย้อนกลับได้</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            @click="$wire.removeBadge(itemToDelete); isDeleteModalOpen = false" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        ยืนยันการเพิกถอน
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
