<div x-data="{ isReopenModalOpen: false }" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('backend.disputes.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    รายละเอียดข้อพิพาท #{{ $dispute->id }}
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
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">สร้างเมื่อ: {{ $dispute->created_at->translatedFormat('d M Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800 inset-0 mb-4 z-50">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Dispute Details -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        หัวข้อ/เหตุผลการเปิดข้อพิพาท
                    </h3>
                </div>
                <div class="p-6">
                    <h4 class="text-xl font-bold text-indigo-700 dark:text-indigo-400 mb-4">{{ $dispute->reason }}</h4>
                    <div class="bg-gray-50 dark:bg-gray-900/20 p-5 rounded-lg border border-gray-100 dark:border-gray-700/50">
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-300 mb-2">รายละเอียดเหตุการณ์:</h4>
                        <p class="text-base text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $dispute->description }}</p>
                    </div>
                    
                    @if($dispute->evidence && is_array($dispute->evidence) && count($dispute->evidence) > 0)
                        <div class="mt-6 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                หลักฐานประกอบ ({{ count($dispute->evidence) }} ภาพ)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($dispute->evidence as $image)
                                    <a href="{{ image_url($image) }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:opacity-75 transition bg-gray-100 dark:bg-gray-900">
                                        <img src="{{ image_url($image) }}" alt="Evidence" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Context -->
            @if($dispute->bookingable)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            ความเชื่อมโยงกับธุรกรรม (Booking Context)
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ประเภทรายการ</p>
                                <p class="text-base font-bold text-gray-900 dark:text-white capitalize">
                                    {{ class_basename(get_class($dispute->bookingable)) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">รหัสอ้างอิง</p>
                                <p class="text-base font-bold text-gray-900 dark:text-white text-right">
                                    #{{ $dispute->bookingable_id }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <!-- Try to display specific details if the model supports it.  -->
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                สถานะปัจจุบันของรายการ: 
                                <span class="font-bold {{ $dispute->bookingable->status === 'completed' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ strtoupper($dispute->bookingable->status ?? 'ไม่ทราบ') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Resolution Action Section -->
            <div class="bg-indigo-50 dark:bg-indigo-900/10 shadow-sm rounded-xl border border-indigo-200 dark:border-indigo-800/50 overflow-hidden" id="resolution_block">
                <div class="px-6 py-5 border-b border-indigo-200 dark:border-indigo-800/50 bg-indigo-100/50 dark:bg-indigo-900/30">
                    <h3 class="text-lg leading-6 font-bold text-indigo-900 dark:text-indigo-300 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                        คำตัดสินของแอดมินคนกลาง (Resolution)
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($dispute->status === 'open')
                        <form wire:submit="resolveDispute">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-indigo-900 dark:text-indigo-300 mb-2">เลือกทิศทางการเคลียร์ข้อพิพาท</label>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none dark:bg-gray-800 dark:border-gray-600">
                                            <input type="radio" wire:model="status" name="status" value="resolved" class="sr-only" />
                                            <span class="flex flex-1">
                                                <span class="flex flex-col">
                                                <span class="block text-sm font-medium text-gray-900 dark:text-white">🤝 ไกล่เกลี่ย/ตกลงกันได้</span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">ทั้งสองฝ่ายยุติข้อพิพาท</span>
                                                </span>
                                            </span>
                                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400 {{ $status === 'resolved' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none dark:bg-gray-800 dark:border-gray-600">
                                            <input type="radio" wire:model="status" name="status" value="closed" class="sr-only" />
                                            <span class="flex flex-1">
                                                <span class="flex flex-col">
                                                <span class="block text-sm font-medium text-gray-900 dark:text-white">🔒 ปิด/ยุติโดยแอดมิน</span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">คำตัดสินถือเป็นที่สิ้นสุด</span>
                                                </span>
                                            </span>
                                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400 {{ $status === 'closed' ? 'block' : 'hidden' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </label>
                                    </div>
                                    @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="resolution" class="block text-sm font-medium text-indigo-900 dark:text-indigo-300 mb-2">บทสรุป / การชดเชย / ข้อตกลงร่วมกัน</label>
                                    <textarea wire:model="resolution" id="resolution" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="ระบุมาตรการดำเนินการ เช่น โอนมัดจำคืน 50%, หรือ แบนบัญชีจำเลย..."></textarea>
                                    @error('resolution') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-indigo-200 dark:border-indigo-800/50">
                                <button type="submit" class="inline-flex justify-center py-2.5 px-6 shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    บันทึกผลการตัดสิน และปิดเคส
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-indigo-100 dark:border-indigo-900 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>    
                            </div>
                            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 mb-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">ผู้รับผิดชอบพิจารณา/ตัดสิน</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white mt-1">{{ $dispute->admin->name ?? 'Admin System' }}</p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">เวลาสรุปผล</h4>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $dispute->updated_at->translatedFormat('d M Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="relative z-10">
                                <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-300 mb-2">บทสรุปดำเนินการ (Resolution Track):</h4>
                                <p class="text-base text-gray-800 dark:text-gray-200 whitespace-pre-line bg-gray-50 dark:bg-gray-900/50 p-4 rounded border border-gray-200 dark:border-gray-700">{{ $dispute->resolution }}</p>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 relative z-10 text-right">
                                <button type="button" @click="isReopenModalOpen = true" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    แก้ไขคำตัดสิน/เปิดคดีใหม่
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>

        <!-- Right Sidebar (Parties Involved) -->
        <div class="space-y-6">
            
            <!-- Plaintiff / Reporter -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/10 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-blue-900 dark:text-blue-400 uppercase tracking-wider">โจทก์ (ผู้ร้อง)</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Reporter</span>
                </div>
                <div class="p-6">
                    @if($dispute->reporter)
                        <div class="flex flex-col items-center text-center space-y-4">
                            <x-backend.avatar :user="$dispute->reporter" size="24" />
                            <div>
                                <a href="{{ route('backend.users.show', $dispute->reporter_id) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-blue-600 transition" target="_blank">
                                    {{ $dispute->reporter->name }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $dispute->reporter->email }}</p>
                            </div>
                            
                            <a href="{{ route('backend.users.show', $dispute->reporter_id) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ดูโปรไฟล์เต็ม
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="text-gray-500 italic">บัญชีถูกลบจากระบบ</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- VS Badge -->
            <div class="flex justify-center -my-3 relative z-10 pointer-events-none">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 border-4 border-white dark:border-gray-900 flex items-center justify-center shadow-sm">
                    <span class="text-red-600 dark:text-red-400 font-bold text-sm">VS</span>
                </div>
            </div>

            <!-- Defendant / Respondent -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900/10 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-red-900 dark:text-red-400 uppercase tracking-wider">จำเลย (ผู้ถูกร้อง)</h3>
                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Respondent</span>
                </div>
                <div class="p-6">
                    @if($dispute->respondent)
                        <div class="flex flex-col items-center text-center space-y-4">
                            <x-backend.avatar :user="$dispute->respondent" size="24" />
                            <div>
                                <a href="{{ route('backend.users.show', $dispute->respondent_id) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-red-600 transition" target="_blank">
                                    {{ $dispute->respondent->name }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $dispute->respondent->email }}</p>
                            </div>
                            
                            <a href="{{ route('backend.users.show', $dispute->respondent_id) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 dark:border-red-600/50 shadow-sm text-sm font-medium rounded-md text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                ดูประวัติผู้ถูกร้อง
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="text-gray-500 italic">บัญชีถูกลบจากระบบ</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Alpine.js Reopen Confirmation Modal -->
    <div x-show="isReopenModalOpen" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="isReopenModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900/75 transition-opacity" 
                 @click="isReopenModalOpen = false" 
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="isReopenModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ยืนยันการรื้อฟื้นข้อพิพาท</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <p>คุณแน่ใจหรือไม่ที่จะรื้อฟื้นข้อพิพาทนี้กลับมาใหม่?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            @click="$wire.reopenDispute(); isReopenModalOpen = false" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        ยืนยันการรื้อฟื้น
                    </button>
                    <button type="button" 
                            @click="isReopenModalOpen = false" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
