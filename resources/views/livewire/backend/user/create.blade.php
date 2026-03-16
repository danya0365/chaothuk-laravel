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
        <form wire:submit="save" class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($permissions as $permission)
                            <div class="p-4 border border-gray-200 rounded-xl dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm flex flex-col space-y-4">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ __('common.permission-' . $permission->slug) ?? $permission->name }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 whitespace-normal break-words">{{ $permission->desc }}</p>
                                </div>
                                
                                <div class="flex items-center space-x-4 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-lg border border-gray-100 dark:border-gray-800">
                                    <!-- อนุญาต (True) -->
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model="permissions_data.{{ $permission->id }}.value" value="true" type="radio" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-xs font-medium text-green-700 dark:text-green-400">อนุญาต</span>
                                    </label>
                                    <!-- ไม่อนุญาต (False) -->
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model="permissions_data.{{ $permission->id }}.value" value="false" type="radio" class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-xs font-medium text-red-700 dark:text-red-400">ไม่อนุญาต</span>
                                    </label>
                                    <!-- ไม่ระบุ (Clear) -->
                                    <label class="flex items-center cursor-pointer opacity-60 hover:opacity-100 transition">
                                        <input wire:model="permissions_data.{{ $permission->id }}.value" value="" type="radio" class="w-4 h-4 text-gray-400 bg-gray-100 border-gray-300 focus:ring-gray-400 dark:focus:ring-gray-500 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-xs font-medium text-gray-500 dark:text-gray-400">ค่าเริ่มต้น</span>
                                    </label>
                                </div>

                                <!-- Description Input (Always shows) -->
                                <div class="mt-2">
                                    <input wire:model="permissions_data.{{ $permission->id }}.desc" type="text" class="block w-full text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="หมายเหตุ/คำอธิบายเพิ่มเติม (ตัวเลือก)">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions_data') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <hr class="col-span-1 md:col-span-2 border-gray-200 dark:border-gray-700 my-4">

                <!-- Profile Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">รูปโปรไฟล์ (Avatar)</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex-shrink-0">
                            @if ($profile_image)
                                <img src="{{ $profile_image->temporaryUrl() }}" class="h-full w-full object-cover">
                            @else
                                <svg class="h-full w-full text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            @endif
                        </div>
                        <input type="file" wire:model="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 transition">
                    </div>
                    @error('profile_image') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">รูปหน้าปก (Cover)</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div class="h-16 w-32 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex-shrink-0">
                            @if ($cover_image)
                                <img src="{{ $cover_image->temporaryUrl() }}" class="h-full w-full object-cover">
                            @else
                                <svg class="h-full w-full text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                            @endif
                        </div>
                        <input type="file" wire:model="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 transition">
                    </div>
                    @error('cover_image') <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Username -->
                <div class="col-span-1 md:col-span-2 mt-4">
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
            <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
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
