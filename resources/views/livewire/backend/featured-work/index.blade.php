<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">รายการโปรโมท (Featured Works)</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                จัดการรายการโปรโมทงานเช่าทั้งหมดในระบบ รวมถึงสามารถยกเลิกสิทธิการโปรโมทล่วงหน้าได้
            </p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-4 mb-4 grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="sm:col-span-2">
            <label for="search" class="sr-only">ค้นหา</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="ค้นหา รหัสงาน, ชื่อโปร์ไฟล์...">
            </div>
        </div>
        
        <!-- Status Filter -->
        <div>
            <select wire:model.live="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                <option value="all">สถานะทั้งหมด</option>
                <option value="active">กำลังโปรโมท (Active)</option>
                <option value="expired">หมดอายุแล้ว (Expired)</option>
            </select>
        </div>

        <!-- Payment Filter -->
        <div>
            <select wire:model.live="paymentMethod" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                <option value="all">ช่องทางจ่ายทั้งหมด</option>
                <option value="wallet">เติมเตรดิต (Wallet)</option>
                <option value="admin_override">แอดมินมอบให้ (Admin Override)</option>
            </select>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/40 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-300">ความสำเร็จ</h3>
                    <div class="mt-2 text-sm text-green-700 dark:text-green-400">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 sm:pl-6">ผู้ใช้งาน / รหัสโปรไฟล์งาน</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">ประเภทโปรโมท</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">วันเวลาที่แสดง</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-gray-200">สถานะ</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">จัดการ</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse ($featuredWorks as $fw)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        @if($fw->work && $fw->work->author)
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $fw->work->author->getAvatar() }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        <a href="{{ route('backend.works.show', $fw->work->id) }}" class="hover:underline">
                                                            {{ $fw->work->code }}
                                                        </a>
                                                    </div>
                                                    <div class="text-gray-500">{{ $fw->work->author->name }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-red-500">ข้อมูลงานถูกลบ</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($fw->payment_method === 'admin_override')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                สิทธิพิเศษจากแอดมิน
                                            </span>
                                            @if($fw->approvedByUser)
                                            <div class="text-xs text-gray-400 mt-1">โดย {{ $fw->approvedByUser->name }}</div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                แลกด้วยเครดิตแพลตฟอร์ม
                                            </span>
                                            <div class="text-xs text-gray-400 mt-1">Paid: ฿{{ number_format($fw->amount_paid, 2) }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div><b>เริ่ม:</b> {{ \Carbon\Carbon::parse($fw->start_at)->format('d M Y, H:i') }}</div>
                                        <div><b>สิ้นสุด:</b> {{ \Carbon\Carbon::parse($fw->end_at)->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                        @if(\Carbon\Carbon::parse($fw->end_at)->isFuture())
                                            <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-900 dark:text-green-300">กำลังแสดง (Active)</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800 dark:bg-gray-700 dark:text-gray-300">หมดสิทธิ (Expired)</span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        @if($fw->work && \Carbon\Carbon::parse($fw->end_at)->isFuture())
                                            <button wire:click="confirmRevokeFeature({{ $fw->id }})" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400">
                                                สั่งยกเลิกสิทธิ <span class="sr-only">, {{ $fw->work->code }}</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">
                                        ไม่พบรายการโปรโมทในระบบ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $featuredWorks->links() }}
    </div>

    <!-- Revoke Feature Confirmation Modal -->
    @if($featuredWorkIdToRevoke)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeRevokeFeatureModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">ยืนยันการสั่งระงับโปรโมทกะทันหัน</h3>
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <p>คุณแน่ใจหรือไม่ที่จะยกเลิกสิทธิรายการนี้? เวลาโปรโมทที่เหลือทั้งหมดจะถูกตัดทิ้งทันที และไม่สามารถกู้คืนได้</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="revokeFeature" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">ยืนยันการตั้งค่า</button>
                        <button wire:click="closeRevokeFeatureModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">ปิดหน้าต่าง</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
