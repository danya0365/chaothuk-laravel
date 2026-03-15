<div class="p-6 sm:p-10 space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $permission && $permission->exists ? 'แก้ไขสิทธิ: ' . $permission->name : 'เพิ่มสิทธิใหม่ (Permission)' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ระบุรายละเอียดและตัวอ้างอิง (Slug) ของสิทธินี้</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('backend.permissions.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                กลับไปหน้ารวมสิทธิ
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form wire:submit="save">
            <div class="p-6 sm:p-8 space-y-6">
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- Permission Name -->
                    <div class="sm:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อสิทธิ (Name)</label>
                        <div class="mt-1">
                            <input type="text" wire:model.live="name" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md placeholder-gray-400" placeholder="เช่น ตั้งค่าระบบ">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Permission Slug -->
                    <div class="sm:col-span-1">
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug อ้างอิง</label>
                        <div class="mt-1">
                            <input type="text" wire:model="slug" id="slug" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md placeholder-gray-400" placeholder="เช่น system.settings">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ควรตั้งเป็นภาษาอังกฤษ ไม่มีเว้นวรรค เช่น 'module.action'</p>
                        @error('slug') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="desc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">คำอธิบายรายละเอียด</label>
                        <div class="mt-1">
                            <textarea wire:model="desc" id="desc" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md placeholder-gray-400" placeholder="อธิบายหน้าที่ของสิทธินี้..."></textarea>
                        </div>
                        @error('desc') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <!-- Footer actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
                <a href="{{ route('backend.permissions.index') }}" wire:navigate class="px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ยกเลิก
                </a>
                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>
