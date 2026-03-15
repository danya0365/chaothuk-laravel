<div class="p-6 sm:p-10 space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $issue && $issue->exists ? 'แก้ไขแคมเปญแจกพอยท์: ' . $issue->name : 'สร้างแคมเปญแจกพอยท์ใหม่' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">กำหนดรายละเอียดเงื่อนไขการแจกพอยท์ และนโยบายอื่นๆ</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('backend.points.issues.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                กลับไประบบจัดการพอยท์
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form wire:submit="save">
            <div class="p-6 sm:p-8 space-y-8">
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-2 lg:grid-cols-3">
                    
                    <!-- Basic Info Group -->
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">ข้อมูลพื้นฐานแคมเปญ</h3>
                    </div>

                    <!-- Name -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ชื่อแคมเปญ/เหตุผล (Name)</label>
                        <div class="mt-1">
                            <input type="text" wire:model.live="name" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md placeholder-gray-400" placeholder="เช่น โบนัสสมัครใหม่">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug อ้างอิง</label>
                        <div class="mt-1">
                            <input type="text" wire:model="slug" id="slug" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md placeholder-gray-400" placeholder="เช่น register_bonus">
                        </div>
                        @error('slug') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Points -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">จำนวนพอยท์ (Points)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" step="0.01" wire:model="points" id="points" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md pr-12 text-right" placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Pts</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ใส่ค่าบวก (+) เพื่อแจก หรือค่าลบ (-) เพื่อหัก</p>
                        @error('points') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <label for="desc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">คำอธิบายรายละเอียดแคมเปญ</label>
                        <div class="mt-1">
                            <textarea wire:model="desc" id="desc" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md placeholder-gray-400" placeholder="อธิบายเงื่อนไข หรือรายละเอียดให้ชัดเจน..."></textarea>
                        </div>
                        @error('desc') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Conditions & Timeline Group -->
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 pt-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">เงื่อนไขเวลาและสถานะ</h3>
                    </div>

                    <!-- Type -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">รูปแบบแคมเปญ (Type)</label>
                        <div class="mt-1">
                            <select wire:model="type" id="type" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="one_time">แบบครั้งเดียว (One Time)</option>
                                <option value="repeat">แบบทำซ้ำ (Repeat)</option>
                            </select>
                        </div>
                        @error('type') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">สถานะอนุมัติ (Status)</label>
                        <div class="mt-1">
                            <select wire:model="status" id="status" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="submit">รอดำเนินการ (Submit)</option>
                                <option value="approve">อนุมัติใช้งาน (Approve)</option>
                                <option value="reject">ปฏิเสธ (Reject)</option>
                            </select>
                        </div>
                        @error('status') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Empty Column for Grid alignment -->
                    <div class="hidden lg:block lg:col-span-1"></div>

                    <!-- Start Date -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="start_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">วันเวลาเริ่มต้น (Start Time)</label>
                        <div class="mt-1">
                            <input type="datetime-local" wire:model="start_at" id="start_at" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        </div>
                        @error('start_at') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <!-- End Date -->
                    <div class="sm:col-span-1 lg:col-span-1">
                        <label for="end_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">วันเวลาสิ้นสุด (End Time)</label>
                        <div class="mt-1">
                            <input type="datetime-local" wire:model="end_at" id="end_at" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                        </div>
                        @error('end_at') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">ปล่อยว่างในช่องเวลาเพื่อเปิดใช้งานแบบไม่มีมีกำหนดเวลา</p>
                    </div>
                    
                </div>
            </div>

            <!-- Footer actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
                <a href="{{ route('backend.points.issues.index') }}" wire:navigate class="px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ยกเลิก
                </a>
                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    บันทึกข้อมูลแคมเปญ
                </button>
            </div>
        </form>
    </div>
</div>
