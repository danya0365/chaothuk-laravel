<div class="space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">จัดการข้อมูลจังหวัด (Provinces)</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ตั้งค่าภูมิภาคและจังหวัดที่จะให้ใช้งานในระบบ</p>
        </div>
        <div>
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มจังหวัดใหม่
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-1 md:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md leading-5 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="ค้นหารหัส, ชื่อจังหวัดไทย/อังกฤษ">
            </div>
            <div class="col-span-1">
                <select wire:model.live="geographyFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option value="">ภูมิภาคทั้งหมด</option>
                    @foreach($geographies as $geo)
                        <option value="{{ $geo->id }}">{{ $geo->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">รหัส</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">จังหวัด</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ภูมิภาค</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">จัดการ</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($provinces as $province)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $province->code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-gray-700" src="{{ image_url($province->image, 'https://ui-avatars.com/api/?name='.urlencode($province->name_en).'&background=random') }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $province->name_th }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $province->name_en }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                    {{ $province->geography?->name ?? 'ไม่ระบุ' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $province->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">แก้ไข</button>
                                <button wire:click="delete({{ $province->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบจังหวัดนี้ สำคัญ: อาจทำให้พังถ้ายึดกับผู้ใช้?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">ลบ</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                ไม่พบข้อมูลจังหวัดที่คุณค้นหา
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $provinces->links() }}
        </div>
    </div>

    <!-- Create / Edit Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form wire:submit.prevent="save">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    {{ $isEditMode ? 'แก้ไขจังหวัด' : 'เพิ่มจังหวัด' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    
                                    <div>
                                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">รหัส (Code)</label>
                                        <input type="text" wire:model.defer="code" id="code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                                        @error('code') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="name_th" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อไทย</label>
                                            <input type="text" wire:model.defer="name_th" id="name_th" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                                            @error('name_th') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่ออักฤษ</label>
                                            <input type="text" wire:model.defer="name_en" id="name_en" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                                            @error('name_en') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="geography_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ภูมิภาค (Geography)</label>
                                        <select wire:model.defer="geography_id" id="geography_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                                            <option value="">เลือกภูมิภาค...</option>
                                            @foreach($geographies as $geo)
                                                <option value="{{ $geo->id }}">{{ $geo->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('geography_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">รูปภาพ (URL)</label>
                                        <input type="text" wire:model.defer="image" id="image" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                                        @error('image') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        <p class="mt-1 text-xs text-gray-500">รองรับ URL รูปภาพไปก่อนในเวอร์ชันนี้ (ตัวอย่าง: https://example.com/bkk.jpg)</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            บันทึกข้อมูล
                        </button>
                        <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
