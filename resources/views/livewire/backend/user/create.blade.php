<div class="p-6 sm:p-10 max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('backend.users.index') }}" wire:navigate class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">เพิ่มสมาชิกใหม่</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">กรอกข้อมูลพื้นฐานเพื่อสร้างบัญชีผู้ใช้งานใหม่ในระบบ</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form wire:submit="save" class="p-6 sm:p-8 space-y-6">
            
                <!-- Roles -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">กลุ่มสิทธิ์การใช้งาน (Roles)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <label class="flex items-start p-4 border border-gray-200 rounded-xl dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input wire:model="role_ids" value="{{ $role->id }}" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ __('common.role-' . $role->id) ?? $role->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('role_ids') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Custom Permissions -->
                <div class="col-span-1 md:col-span-2 mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">สิทธิ์เพิ่มเติมเฉพาะบุคคล (Custom Permissions)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <label class="flex items-start p-4 border border-gray-200 rounded-xl dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input wire:model="permission_ids" value="{{ $permission->id }}" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ __('common.permission-' . $permission->slug) ?? $permission->name }}</span>
                                    @if($permission->desc)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $permission->desc }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('permission_ids') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <hr class="col-span-1 md:col-span-2 border-gray-200 dark:border-gray-700 my-4">

                <!-- Username -->
                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อแสดงผล (Username) <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" id="name" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="เช่น somchai123">
                    @error('name') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อจริง</label>
                    <input wire:model="first_name" type="text" id="first_name" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('first_name') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">นามสกุล</label>
                    <input wire:model="last_name" type="text" id="last_name" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('last_name') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">อีเมล <span class="text-red-500">*</span></label>
                    <input wire:model="email" type="email" id="email" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="email@example.com">
                    @error('email') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Complete Phone -->
                <div>
                    <label for="mobile_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">เบอร์โทรศัพท์</label>
                    <input wire:model="mobile_phone" type="text" id="mobile_phone" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="08xxxxxxxx">
                    @error('mobile_phone') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">รหัสผ่าน <span class="text-red-500">*</span></label>
                    <input wire:model="password" type="password" id="password" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="••••••••">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">อย่างน้อย 8 ตัวอักษร</p>
                    @error('password') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ยืนยันรหัสผ่าน <span class="text-red-500">*</span></label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="••••••••">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
                <a href="{{ route('backend.users.index') }}" wire:navigate class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition">
                    ยกเลิก
                </a>
                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">บันทึกข้อมูล</span>
                    <span wire:loading wire:target="save">กำลังบันทึก...</span>
                </button>
            </div>
        </form>
    </div>
</div>
