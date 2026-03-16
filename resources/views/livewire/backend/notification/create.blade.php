<div>
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('backend.notifications.index') }}" class="p-2 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                    ส่งการแจ้งเตือน (Broadcast)
                </h2>
                <p class="text-gray-400 text-sm mt-1">
                    ส่ง Push/In-App Notification ไปยังผู้ใช้งานในระบบ
                </p>
            </div>
        </div>

        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 shadow-sm">
            <form wire:submit.prevent="sendBroadcast" class="space-y-8">
                
                <!-- Target Selection -->
                <div>
                    <h3 class="text-lg font-medium text-gray-200 mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 text-sm">1</span>
                        เลือกกลุ่มผู้รับ
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="relative flex cursor-pointer rounded-lg border bg-slate-900/50 p-4 shadow-sm focus:outline-none transition-colors {{ $targetType === 'all' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-500/5' : 'border-slate-700/50 hover:border-slate-600 hover:bg-slate-800' }}">
                            <input type="radio" wire:model.live="targetType" value="all" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium {{ $targetType === 'all' ? 'text-indigo-400' : 'text-gray-300' }}">ส่งให้ทุกคน (All Users)</span>
                                    <span class="mt-1 flex text-xs text-gray-500">ผู้ใช้งานทุกคนในระบบจะได้รับการแจ้งเตือนนี้</span>
                                </span>
                            </span>
                            @if($targetType === 'all')
                                <svg class="h-5 w-5 text-indigo-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </label>

                        <label class="relative flex cursor-pointer rounded-lg border bg-slate-900/50 p-4 shadow-sm focus:outline-none transition-colors {{ $targetType === 'role' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-500/5' : 'border-slate-700/50 hover:border-slate-600 hover:bg-slate-800' }}">
                            <input type="radio" wire:model.live="targetType" value="role" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium {{ $targetType === 'role' ? 'text-indigo-400' : 'text-gray-300' }}">ระดับผู้ใช้งาน (By Role)</span>
                                    <span class="mt-1 flex text-xs text-gray-500">เลือกส่งเฉพาะกลุ่มบทบาทที่กำหนด</span>
                                </span>
                            </span>
                            @if($targetType === 'role')
                                <svg class="h-5 w-5 text-indigo-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </label>

                        <label class="relative flex cursor-pointer rounded-lg border bg-slate-900/50 p-4 shadow-sm focus:outline-none transition-colors {{ $targetType === 'user' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-500/5' : 'border-slate-700/50 hover:border-slate-600 hover:bg-slate-800' }}">
                            <input type="radio" wire:model.live="targetType" value="user" class="sr-only">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium {{ $targetType === 'user' ? 'text-indigo-400' : 'text-gray-300' }}">ระบุรายบุคคล (Specific User)</span>
                                    <span class="mt-1 flex text-xs text-gray-500">ระบุอีเมลผู้ใช้เพื่อส่งโดยตรง</span>
                                </span>
                            </span>
                            @if($targetType === 'user')
                                <svg class="h-5 w-5 text-indigo-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </label>
                    </div>

                    <!-- Dynamic Target Inputs -->
                    <div class="mt-4">
                        @if($targetType === 'role')
                            <div>
                                <label for="roleId" class="block text-sm font-medium text-gray-400">เลือกบทบาท (Role)</label>
                                <select wire:model="roleId" id="roleId" class="mt-1 block w-full rounded-lg bg-slate-900/50 border-slate-700/50 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-200 shadow-sm">
                                    <option value="">-- กรุณาเลือกบทบาท --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('roleId') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @elseif($targetType === 'user')
                            <div>
                                <label for="userEmail" class="block text-sm font-medium text-gray-400">อีเมลผู้รับ (User Email)</label>
                                <input type="email" wire:model="userEmail" id="userEmail" placeholder="example@email.com" class="mt-1 block w-full rounded-lg bg-slate-900/50 border-slate-700/50 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-200 shadow-sm placeholder-gray-600">
                                @error('userEmail') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="border-slate-700/50 hover:border-slate-600 transition-colors">

                <!-- Message Content -->
                <div>
                    <h3 class="text-lg font-medium text-gray-200 mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 text-sm">2</span>
                        เนื้อหาการแจ้งเตือน
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-400">ประเภทข้อความ (Notification Type)</label>
                            <select wire:model="type" id="type" class="mt-1 block w-full rounded-lg bg-slate-900/50 border-slate-700/50 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-200 shadow-sm">
                                @foreach($notificationTypes as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-400">หัวข้อหลัก (Title)</label>
                            <input type="text" wire:model="title" id="title" placeholder="เช่น: มีอัพเดตใหม่จากระบบ!" class="mt-1 block w-full rounded-lg bg-slate-900/50 border-slate-700/50 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-200 shadow-sm placeholder-gray-600 font-medium">
                            @error('title') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-400">รายละเอียด (Message/Details)</label>
                            <textarea wire:model="message" id="message" rows="4" placeholder="พิมพ์ข้อความที่ต้องการแจ้งเตือน..." class="mt-1 block w-full rounded-lg bg-slate-900/50 border-slate-700/50 focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-200 shadow-sm placeholder-gray-600"></textarea>
                            @error('message') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-700/50">
                    <a href="{{ route('backend.notifications.index') }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors bg-slate-800 hover:bg-slate-700 rounded-lg">
                        ยกเลิก
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                        <svg wire:loading.remove wire:target="sendBroadcast" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        <svg wire:loading wire:target="sendBroadcast" class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        ยืนยันการส่งข้อความ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
