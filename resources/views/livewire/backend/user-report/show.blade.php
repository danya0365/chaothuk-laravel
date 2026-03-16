<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header Strategy -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('backend.reports.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    รายละเอียดการรายงาน
                    @if($report->status === 'resolved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                            ตรวจสอบแล้ว
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                            รอดำเนินการ
                        </span>
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">แจ้งเมื่อ: {{ $report->created_at->translatedFormat('d M Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Report Details Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden col-span-1 md:col-span-2">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    ข้อมูลการร้องเรียน
                </h3>
                <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-indigo-200 dark:border-indigo-800">
                    {{ $report->report_type }}
                </span>
            </div>
            <div class="p-6">
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-900">
                    <h4 class="text-sm font-bold text-yellow-800 dark:text-yellow-400 mb-2">รายละเอียด:</h4>
                    <p class="text-sm text-gray-800 dark:text-gray-300 whitespace-pre-line">{{ $report->description }}</p>
                </div>
            </div>
        </div>

        <!-- Reporter -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">ผู้แจ้ง/ร้องเรียน</h3>
            </div>
            <div class="p-6">
                @if($report->reporter)
                    <div class="flex items-center space-x-4">
                        <x-backend.avatar :user="$report->reporter" size="14" />
                        <div>
                            <a href="{{ route('backend.users.show', $report->reporter_id) }}" class="text-base font-bold text-gray-900 dark:text-white hover:text-indigo-600 transition" target="_blank">
                                {{ $report->reporter->name }}
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reporter->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">ID: {{ $report->reporter_id }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic text-sm">ผู้เล่นทั่วไป (Guest) หรือไม่พบข้อมูลในระบบ</p>
                @endif
            </div>
        </div>

        <!-- Reported User -->
        <div class="bg-red-50 dark:bg-red-900/10 shadow-sm rounded-xl border border-red-200 dark:border-red-900/30 overflow-hidden relative">
            <div class="px-6 py-4 border-b border-red-200 dark:border-red-900/30 bg-red-100/50 dark:bg-red-900/20">
                <h3 class="text-sm font-bold text-red-800 dark:text-red-400 uppercase tracking-wider">ผู้ถูกรายงาน</h3>
            </div>
            <div class="p-6">
                @if($report->reportedUser)
                    <div class="flex items-center space-x-4 relative z-10">
                        <x-backend.avatar :user="$report->reportedUser" size="14" />
                        <div>
                            <a href="{{ route('backend.users.show', $report->reported_user_id) }}" class="text-base font-bold text-gray-900 dark:text-white hover:text-red-600 transition" target="_blank">
                                {{ $report->reportedUser->name }}
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reportedUser->email }}</p>
                            <p class="text-xs text-red-500 mt-1">ID: {{ $report->reported_user_id }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-red-200 dark:border-red-800/50 flex gap-2">
                        <!-- Quick Moderation Actions Concept -->
                        <a href="{{ route('backend.users.show', $report->reported_user_id) }}" target="_blank" class="w-full text-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            ตรวจสอบประวัติผู้ใช้นี้
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 italic text-sm">ไม่พบข้อมูลในระบบ หรือถูกลบไปแล้ว</p>
                @endif
            </div>
        </div>
        
        <!-- Resolution Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden col-span-1 md:col-span-2 mt-2">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    การดำเนินการของแอดมิน
                </h3>
            </div>
            
            <div class="p-6">
                @if($report->status === 'resolved')
                    <div class="bg-green-50 dark:bg-green-900/20 p-5 rounded-lg border border-green-200 dark:border-green-800 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-400">ปิดงานและตรวจสอบเรียบร้อยแล้ว</h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    <p><span class="font-semibold">ผู้ดำเนินการ:</span> {{ $report->resolver->name ?? 'Admin' }}</p>
                                    <p><span class="font-semibold">เวลา:</span> {{ $report->resolved_at->translatedFormat('d M Y H:i:s') }}</p>
                                    <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded border border-green-100 dark:border-green-900">
                                        <p class="font-semibold mb-1 text-gray-700 dark:text-gray-300">บันทึกการจัดการ:</p>
                                        <p class="text-gray-600 dark:text-gray-400 break-words">{{ $report->admin_note }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" wire:click="reopenReport" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        เปิดเรื่องกลับมาพิจารณาใหม่
                    </button>
                    
                @else
                    <form wire:submit="resolveReport">
                        <div class="mb-4">
                            <label for="admin_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">บทสรุป / บันทึกการดำเนินการ (Internal Note)</label>
                            <textarea wire:model="admin_note" id="admin_note" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="ระบุการตักเตือน แบน หรือเหตุผลที่เพิกเฉย..."></textarea>
                            @error('admin_note') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                บันทึกและทำเครื่องหมายว่าตรวจสอบแล้ว
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
