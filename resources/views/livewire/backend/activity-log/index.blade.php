<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 text-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ประวัติกิจกรรมระบบ (Activity Logs)</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">ตรวจสอบประวัติการใช้งานและการกระทำของระบบทั้งหมด</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="search" class="sr-only">ค้นหา</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" id="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" placeholder="ค้นหาตามชื่อ/อีเมลผู้ใช้ หรือรายละเอียดกิจกรรม...">
                </div>
            </div>
            
            <div>
                <label for="typeFilter" class="sr-only">กรองประเภทกิจกรรม</label>
                <select wire:model.live="typeFilter" id="typeFilter" class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">-- กรองประเภทกิจกรรมทั้งหมด --</option>
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}">{{ __('common.user_activity_type-' . $type) ?? $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">วันเวลา (Timestamp)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ผู้ทำรายการ (User)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ประเภท (Type)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">รายละเอียด (Details)</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                @if($log->user)
                                <div class="flex items-center space-x-3">
                                    <x-backend.avatar :src="$log->user->getAvatar()" :name="$log->user->name" size="h-8 w-8" />
                                    <div>
                                        <a href="{{ route('backend.users.show', $log->user->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $log->user->name }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email }}</div>
                                    </div>
                                </div>
                                @else
                                    <span class="text-gray-500 italic">System / Deleted User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    {{ $log->getActivityTypeFormat() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $value = $log->activity_value;
                                    $isJson = is_string($value) && is_array(json_decode($value, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
                                @endphp
                                @if($isJson)
                                    <pre class="bg-gray-50 dark:bg-gray-900 p-2 rounded text-xs overflow-x-auto max-w-sm">{{ json_encode(json_decode($value), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @else
                                    <span class="break-words line-clamp-2" title="{{ $value }}">{{ Str::limit($value, 100) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                ไม่พบข้อมูลประวัติกิจกรรม
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
