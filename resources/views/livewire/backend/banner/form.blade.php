<div class="p-6 sm:p-10 max-w-4xl mx-auto space-y-6">
    <!-- Header Strategy -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('backend.banners.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $isEditMode ? 'แก้ไขแบนเนอร์' : 'อัปโหลดแบนเนอร์ใหม่' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $isEditMode ? 'แก้ไขข้อมูลของแบนเนอร์ #' . $banner->id : 'ระบุข้อมูลและอัปโหลดรูปภาพแบนเนอร์เพื่อแสดงบนหน้าแรก' }}
            </p>
        </div>
    </div>

    <!-- Form Panel -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden relative">
        <div wire:loading.flex wire:target="save, image, removeExistingImage" class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 z-10 flex flex-col items-center justify-center backdrop-blur-sm">
            <svg class="animate-spin h-10 w-10 text-indigo-600 dark:text-indigo-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">กำลังประมวลผล...</span>
        </div>

        <form wire:submit="save" class="divide-y divide-gray-200 dark:divide-gray-700">
            <div class="p-6 sm:p-8 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Banner Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อแบนเนอร์ (อ้างอิงภายใน) <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" wire:model="name" id="name" class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror" placeholder="เช่น โปรโมชั่นสงกรานต์ 2567" autofocus>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ประเภทลิงก์ปลายทาง <span class="text-red-500">*</span></label>
                        <select wire:model="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md appearance-none">
                            <option value="external_url">ไปที่ลิงก์ภายนอก URL ทั่วไป</option>
                            <option value="product">ลิงก์หน้างานเช่าเฉพาะ (Product)</option>
                            <option value="promotion">ลิงก์หน้าแคมเปญโปรโมชั่น</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- External URL -->
                    <div>
                        <label for="external_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL ปลายทาง เมื่อคลิก (Optional)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="url" wire:model="external_url" id="external_url" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="https://example.com/promo">
                        </div>
                        @error('external_url')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">การแสดงผล</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Toggle -->
                        <div class="flex items-center">
                            <button type="button" wire:click="$toggle('is_public')" class="{{ $is_public ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }} relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" role="switch" aria-checked="true">
                                <span aria-hidden="true" class="{{ $is_public ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                            </button>
                            <span class="ml-3 font-medium text-sm text-gray-900 dark:text-gray-300">เปิดใช้งาน (แสดงชิ้นงานบนหน้าแรก)</span>
                        </div>

                        <!-- Pinned Toggle -->
                        <div class="flex items-center">
                            <button type="button" wire:click="$toggle('is_pinned')" class="{{ $is_pinned ? 'bg-amber-500' : 'bg-gray-200 dark:bg-gray-600' }} relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500" role="switch" aria-checked="false">
                                <span aria-hidden="true" class="{{ $is_pinned ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                            </button>
                            <span class="ml-3 font-medium text-sm text-gray-900 dark:text-gray-300">ปักหมุดให้อยู่ด้านบนสุดเสมอ</span>
                        </div>
                    </div>
                </div>

                <!-- Expiration Control -->
                <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="has_expiration" wire:model.live="has_expiration" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="has_expiration" class="font-medium text-gray-700 dark:text-gray-300">ตั้งวันหมดอายุแบนเนอร์อัตโนมัติ</label>
                            <p class="text-gray-500 dark:text-gray-400">ระบบจะซ่อนแบนเนอร์นี้เมื่อถึงวันที่และเวลาที่กำหนด</p>
                        </div>
                    </div>

                    @if($has_expiration)
                        <div class="mt-4 ml-7">
                            <label for="expired_at" class="sr-only">วันหมดอายุ</label>
                            <input type="datetime-local" wire:model="expired_at" id="expired_at" class="block w-full md:w-1/2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('expired_at')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">อัปโหลดรูปภาพแบนเนอร์ <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">แนะนำขนาดภาพแนวนอน (Landscape) เพื่อการแสดงผลที่เหมาะสมบน Carousel</p>
                    
                    <div>
                        <!-- Preview Box -->
                        @if ($image)
                            <div class="relative w-full aspect-[21/9] md:aspect-[3/1] rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 mb-4 bg-gray-100 dark:bg-gray-800">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @elseif ($existingImageUrl)
                            <div class="relative w-full aspect-[21/9] md:aspect-[3/1] rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 mb-4 group bg-gray-100 dark:bg-gray-800 bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpCNTZEOUIzNEVCQjQxMUUyOThDNjk5OUNBRkFEQkUwNiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCNTZEOUIzNUVCQjQxMUUyOThDNjk5OUNBRkFEQkUwNiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkI1NkQ5QjMyRUJCNDExRTI5OEM2OTk5Q0FGQURCRTA2IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkI1NkQ5QjMzRUJCNDExRTI5OEM2OTk5Q0FGQURCRTA2Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+P2WbEwAAADBJREFUeNpi/P//PwMhwMJQAKOBOgYxgZGRkZGRkZGRkZGRkZGRkZGRkZGRkRGAAQCXjA31/P4e/AAAAABJRU5ErkJggg==')]">
                                <img src="{{ Storage::url($existingImageUrl) }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="removeExistingImage" class="absolute inset-0 bg-red-600 bg-opacity-75 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="text-center font-medium text-white flex flex-col items-center">
                                        <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        ลุกภาพนี้ทิ้ง
                                    </div>
                                </button>
                            </div>
                        @else
                            <div class="mt-1 flex justify-center px-6 pt-12 pb-12 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 py-1 px-3 border border-indigo-200 dark:border-indigo-800 transition-colors">
                                            <span>เลือกไฟล์รูปภาพแบนเนอร์</span>
                                            <input id="file-upload" wire:model="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, WEBP, GIF ขนาดไม่เกิน 5MB</p>
                                </div>
                            </div>
                        @endif
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
            
            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-end gap-3 rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('backend.banners.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    ยกเลิก
                </a>
                <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    {{ $isEditMode ? 'บันทึกการแก้ไขแบนเนอร์' : 'อัปโหลดแบนเนอร์' }}
                </button>
            </div>
        </form>
    </div>
</div>
