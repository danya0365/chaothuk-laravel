<div>
    <x-slot name="title">{{ $isEditMode ? 'แก้ไขแฟ้มผลงาน' : 'เพิ่มแฟ้มผลงาน' }}</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('backend.portfolios.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $isEditMode ? 'แก้ไขแฟ้มผลงาน' : 'เพิ่มแฟ้มผลงาน' }}
                </h1>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $isEditMode ? 'แก้ไขรายละเอียดของผลงานนี้' : 'สร้างผลงานชิ้นใหม่' }}
            </p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form wire:submit="save">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            ชื่อผลงาน <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="title" id="title" class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Owner (User) Autocomplete -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <label for="userSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            เจ้าของผลงาน (ผู้ใช้งาน) <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Hidden Input for actual value -->
                        <input type="hidden" wire:model="user_id">
                        
                        <div class="relative">
                            @if($selectedUserName)
                                <!-- Selected State -->
                                <div class="flex items-center justify-between w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700/50 text-sm">
                                    <span class="text-gray-900 dark:text-white font-medium truncate">{{ $selectedUserName }}</span>
                                    <button type="button" wire:click="clearUser" class="text-gray-400 hover:text-red-500 focus:outline-none flex-shrink-0">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <!-- Search Input State -->
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        wire:model.live.debounce.300ms="userSearch" 
                                        @focus="open = true"
                                        @input="open = true"
                                        placeholder="พิมพ์ชื่อ หรือ อีเมล เพื่อค้นหา (ขั้นต่ำ 2 ตัวอักษร)..."
                                        class="block w-full pl-9 pr-3 py-2 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                    <div wire:loading wire:target="userSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="animate-spin h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Dropdown Results -->
                        @if(!$selectedUserName && strlen($userSearch) >= 2)
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1 max-h-60 overflow-auto"
                                 style="display: none;">
                                 
                                @if($searchResults->count() > 0)
                                    <ul class="text-sm">
                                        @foreach($searchResults as $user)
                                            <li>
                                                <button type="button" 
                                                        wire:click="selectUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                                        @click="open = false"
                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:outline-none flex items-center justify-between group">
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                                    </div>
                                                    <span class="text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        ไม่พบผู้ใช้งานที่ตรงกับ "{{ $userSearch }}"
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        @error('user_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Work Type -->
                    <div>
                        <label for="work_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            ประเภทงาน <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="work_type_id" id="work_type_id" class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- เลือกประเภทงาน --</option>
                            @foreach($workTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                            @endforeach
                        </select>
                        @error('work_type_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            รายละเอียดผลงาน <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="description" id="description" rows="5" class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Images Gallery -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">อัลบั้มรูปภาพผลงาน</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
                        <!-- Existing Images -->
                        @foreach($existingImages as $index => $imageUrl)
                            <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="removeExistingImage({{ $index }})" class="absolute top-2 right-2 bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach

                        <!-- Newly Uploaded Images (Preview) -->
                        @if($new_images)
                            @foreach($new_images as $index => $image)
                                <div class="relative group aspect-square rounded-lg overflow-hidden border-2 border-green-500 border-dashed bg-green-50 dark:bg-green-900/20">
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-green-600 text-white text-[10px] text-center py-0.5">ใหม่</span>
                                    <button type="button" wire:click="removeNewImage({{ $index }})" class="absolute top-2 right-2 bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif

                        <!-- Upload Button -->
                        <div class="aspect-square relative flex items-center justify-center p-4 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-indigo-500 dark:hover:border-indigo-400 group cursor-pointer transition-colors bg-gray-50 dark:bg-gray-800/50">
                            <input type="file" wire:model="new_images" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-gray-300 group-hover:text-indigo-500 transition-colors">
                                    เพิ่มรูปภาพ
                                </span>
                            </div>
                        </div>
                    </div>
                    @error('new_images.*') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror

                    <div wire:loading wire:target="new_images" class="text-sm text-indigo-600 flex items-center mt-2">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        กำลังอัปโหลดรูปภาพตัวอย่าง...
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
                    <a href="{{ route('backend.portfolios.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        ยกเลิก
                    </a>
                    
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition relative">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEditMode ? 'บันทึกการแก้ไข' : 'สร้างแฟ้มผลงาน' }}
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            กำลังบันทึก...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
