<div class="space-y-6">
    <!-- Header Strategy -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">จัดการการยืนยันตัวตน (Verification)</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                รายชื่อเอกสารยืนยันตัวตนของผู้ใช้งานระบบ สามารถเพิ่มข้อมูลด้วยตนเองได้ในกรณีจำเป็น
            </p>
        </div>
        <button wire:click="openModal" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            เพิ่มข้อมูลการยืนยัน
        </button>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800 transition-all">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-400">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Verifications List -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($verifications as $ver)
                <li class="p-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $ver->label }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    อัปเดตเมื่อ: {{ $ver->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            @if($ver->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800">รอดำเนินการ</span>
                            @elseif($ver->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">อนุมัติแล้ว</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">ไม่อนุมัติ</span>
                            @endif
                            <a href="{{ route('backend.verifications.show', $ver->id) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mt-2 font-medium" target="_blank">ดูรายละเอียด &rarr;</a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    ผู้ใช้งานรายนี้ยังไม่มีข้อมูลการยืนยันตัวตน
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Create Verification Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data x-init="document.body.classList.add('overflow-hidden')" @destroyed="document.body.classList.remove('overflow-hidden')">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form wire:submit="saveVerification">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                เพิ่มข้อมูลการยืนยันตัวตน (Manual)
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                การเพิ่มข้อมูลตรงนี้จะถูกบันทึกว่าเป็นการดำเนินการโดยผู้ดูแลระบบ
                            </p>
                        </div>
                        <div class="px-4 py-5 sm:p-6 space-y-4">
                            <!-- Type Selection -->
                            <div>
                                <label for="verification_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ประเภทเอกสาร <span class="text-red-500">*</span></label>
                                <select id="verification_type" wire:model="verification_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                                    <option value="">-- เลือกประเภทเอกสาร --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->value }}">{{ $type->value }}</option>
                                    @endforeach
                                </select>
                                @error('verification_type') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status Selection -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">สถานะ <span class="text-red-500">*</span></label>
                                <select id="status" wire:model="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                                    <option value="pending">⏳ รอดำเนินการ (Pending)</option>
                                    <option value="approved">✅ อนุมัติแล้ว (Approved)</option>
                                    <option value="rejected">❌ ไม่อนุมัติ (Rejected)</option>
                                </select>
                                @error('status') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Admin Note -->
                            <div>
                                <label for="admin_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">บันทึกช่วยจำ (Admin Note)</label>
                                <div class="mt-1">
                                    <textarea id="admin_note" wire:model="admin_note" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="ระบุเหตุผล เช่น เอกสารผ่านการตรวจจาก Line OA..."></textarea>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">บันทึกนี้จะถูกเก็บไว้เป็นหลักฐานการเพิ่มข้อมูลด้วยตนเอง</p>
                                @error('admin_note') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                บันทึกข้อมูล
                            </button>
                            <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                ยกเลิก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
