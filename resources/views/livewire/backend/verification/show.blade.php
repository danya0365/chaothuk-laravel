<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 text-sm">
        <div class="flex items-center space-x-4">
            <a href="{{ route('backend.verifications.index') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ทบทวนการยืนยันตัวตน</h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">ตรวจสอบความถูกต้องของเอกสาร</p>
            </div>
        </div>
        <div>
            @if($verification->status === 'pending')
            <div class="flex space-x-3">
                <button wire:click="openRejectModal" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    ปฏิเสธเอกสาร
                </button>
                <button wire:click="approve" wire:confirm="คุณแน่ใจหรือไม่ที่จะ อนุมัติ การยืนยันตัวตนนี้?" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    อนุมัติการยืนยันตัวตน
                </button>
            </div>
            @else
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $verification->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                สถานะ: {{ $verification->status === 'approved' ? 'อนุมัติแล้ว' : 'ปฏิเสธแล้ว' }} 
                ({{ $verification->verified_at->format('d/m/Y H:i') }})
            </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: User Details & Metadata -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <x-backend.avatar :src="$verification->user->getAvatar()" :name="$verification->user->name" size="h-12 w-12" class="mr-4" />
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $verification->user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $verification->user->email }}</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ประเภทเอกสาร (Type)</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-semibold">{{ $verification->label }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">สถานะปัจจุบัน (Status)</dt>
                            <dd class="text-base">
                                @if($verification->status === 'pending')
                                    <span class="text-amber-600 dark:text-amber-400 font-medium flex items-center">
                                        <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg> รอดำเนินการ
                                    </span>
                                @elseif($verification->status === 'approved')
                                    <span class="text-green-600 dark:text-green-400 font-medium flex items-center">
                                        <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg> อนุมัติแล้ว
                                    </span>
                                @else
                                    <span class="text-red-600 dark:text-red-400 font-medium flex items-center">
                                        <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg> ปฏิเสธ
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">วันที่ส่งข้อมูล (Submitted At)</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $verification->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                        
                        @if($verification->status !== 'pending')
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ตรวจสอบโดย (Verified By)</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">Admin ID #{{ $verification->verified_by }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">วันที่ตรวจสอบ (Verified At)</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $verification->verified_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                        @if(isset($verification->proof_data['reject_reason']))
                        <div class="mt-4 bg-red-50 dark:bg-red-900/20 rounded-lg p-3 border border-red-100 dark:border-red-900/30">
                            <dt class="text-sm font-medium text-red-800 dark:text-red-400 mb-1">เหตุผลที่ปฏิเสธ</dt>
                            <dd class="text-sm text-red-700 dark:text-red-300">{{ $verification->proof_data['reject_reason'] }}</dd>
                        </div>
                        @endif
                        @endif
                    </dl>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('backend.users.show', $verification->user->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-medium flex items-center" target="_blank">
                        ดูโปรไฟล์ผู้ใช้นี้ฉบับเต็ม
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Document View -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 h-full flex flex-col">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">ข้อมูลเอกสารที่แนบมา (Proof Data)</h3>
                </div>
                
                <div class="p-6 flex-1 bg-gray-50 dark:bg-gray-900/30">
                    @if(is_array($verification->proof_data) && count($verification->proof_data) > 0)
                        <div class="space-y-6">
                            @foreach($verification->proof_data as $key => $value)
                                @if($key === 'reject_reason')
                                    @continue
                                @endif
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize mb-2">{{ str_replace('_', ' ', $key) }}</h4>
                                    
                                    @if(is_string($value) && (str_starts_with($value, 'http') || preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $value)))
                                        <!-- Image Display -->
                                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-white dark:bg-gray-800 max-w-2xl">
                                            <a href="{{ $value }}" target="_blank" class="block group relative">
                                                <img src="{{ $value }}" alt="{{ $key }}" class="w-full h-auto object-contain max-h-96">
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                                    <span class="text-white text-sm font-medium flex items-center">
                                                        <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg> ดูรูปขนาดเต็ม
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    @else
                                        <!-- Text/Data Display -->
                                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <p class="text-base text-gray-900 dark:text-white font-mono break-all">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">ไม่พบข้อมูลแนบเพิ่มเติม</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">การยืนยันนี้ไม่มีไฟล์รูปภาพหรือข้อมูลเพิ่มเติม (Proof Data is empty)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Reject Modal -->
    @if($showRejectModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data x-init="document.body.classList.add('overflow-hidden')" @destroyed="document.body.classList.remove('overflow-hidden')">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeRejectModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form wire:submit="reject">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                        ปฏิเสธการยืนยันตัวตน
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                            โปรดระบุเหตุผลที่ปฏิเสธเอกสารชุดนี้ เพื่อให้ผู้ใช้งานทราบและแก้ไขข้อผิดพลาด
                                        </p>
                                        <textarea wire:model="rejectReason" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="เช่น รูปภาพไม่ชัดเจน, บัตรหมดอายุแล้ว..."></textarea>
                                        @error('rejectReason') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                ยืนยันการปฏิเสธ
                            </button>
                            <button wire:click="closeRejectModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                ยกเลิก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
