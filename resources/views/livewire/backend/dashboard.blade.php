<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ภาพรวมระบบ') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">ภาพรวมระบบ (Overview)</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">อัปเดตข้อมูลแบบเรียลไทม์ เพื่อการตัดสินใจและบริหารจัดการที่รวดเร็ว</p>
            </div>
            <div>
                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 dark:bg-indigo-400/10 dark:text-indigo-400 dark:ring-indigo-400/30">
                    <svg class="h-3 w-3 mr-1.5 fill-current animate-pulse" viewBox="0 0 6 6" aria-hidden="true"><circle cx="3" cy="3" r="3" /></svg>
                    ระบบทำงานปกติ
                </span>
            </div>
        </div>

        <!-- 🚨 Urgent Action Alerts 🚨 -->
        @if(array_sum($alerts) > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($alerts['pending_verifications'] > 0)
            <a href="{{ route('backend.verifications.index') }}" class="group relative overflow-hidden rounded-xl bg-orange-50 dark:bg-orange-900/20 px-6 py-5 shadow-sm ring-1 ring-orange-200 dark:ring-orange-800/50 hover:bg-orange-100 dark:hover:bg-orange-900/40 transition-all flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-500/10 text-orange-600 dark:text-orange-400 rounded-lg p-3 group-hover:bg-orange-500/20 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-orange-800 dark:text-orange-300">ยืนยันตัวตน (KYC)</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-0.5">รอส่งให้คุณอนุมัติ</p>
                    </div>
                </div>
                <div class="text-2xl font-bold text-orange-700 dark:text-orange-400">{{ $alerts['pending_verifications'] }}</div>
            </a>
            @endif

            @if($alerts['open_disputes'] > 0)
            <a href="{{ route('backend.disputes.index') }}" class="group relative overflow-hidden rounded-xl bg-red-50 dark:bg-red-900/20 px-6 py-5 shadow-sm ring-1 ring-red-200 dark:ring-red-800/50 hover:bg-red-100 dark:hover:bg-red-900/40 transition-all flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg p-3 group-hover:bg-red-500/20 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">ข้อพิพาท (Disputes)</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-0.5">ต้องการคนกลางเจรจาเร่งด่วน</p>
                    </div>
                </div>
                <div class="text-2xl font-bold text-red-700 dark:text-red-400">{{ $alerts['open_disputes'] }}</div>
            </a>
            @endif

            @if($alerts['pending_reports'] > 0)
            <a href="{{ route('backend.reports.index') }}" class="group relative overflow-hidden rounded-xl bg-yellow-50 dark:bg-yellow-900/20 px-6 py-5 shadow-sm ring-1 ring-yellow-200 dark:ring-yellow-800/50 hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition-all flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 rounded-lg p-3 group-hover:bg-yellow-500/20 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">รายงาน (Reports)</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-0.5">รอการตรวจสอบเนื้อหา</p>
                    </div>
                </div>
                <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">{{ $alerts['pending_reports'] }}</div>
            </a>
            @endif
        </div>
        @endif

        <!-- 📊 KPIs Grid -->
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">ตัวชี้วัดแพลตฟอร์ม (Platform KPIs)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Users KPI -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 bg-blue-50 dark:bg-blue-900/10 rounded-full w-24 h-24 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ผู้ใช้งานรวม</p>
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg text-blue-600 dark:text-blue-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['total_users']) }}</h3>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-green-600 dark:text-green-400 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            +{{ number_format($metrics['new_users_7d']) }}
                        </span>
                        <span class="text-gray-400 dark:text-gray-500 ml-2">ผู้ใช้ใหม่ใน 7 วัน</span>
                    </div>
                </div>
            </div>

            <!-- Sessions KPI -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 bg-emerald-50 dark:bg-emerald-900/10 rounded-full w-24 h-24 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">เซสชันงานที่กำลังรัน</p>
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg text-emerald-600 dark:text-emerald-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['active_sessions']) }}</h3>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium flex items-center">
                            {{ number_format($metrics['completed_sessions_7d']) }}
                        </span>
                        <span class="text-gray-400 dark:text-gray-500 ml-2">งานที่เสร็จสิ้นใน 7 วัน</span>
                    </div>
                </div>
            </div>

            <!-- Services KPI -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 bg-purple-50 dark:bg-purple-900/10 rounded-full w-24 h-24 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">บริการ (Works) บนระบบ</p>
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg text-purple-600 dark:text-purple-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['active_works']) }}</h3>
                        <span class="text-sm text-gray-500 font-medium">เปิดใช้งาน</span>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-400 dark:text-gray-500">
                        จากทั้งหมด {{ number_format($metrics['total_works']) }} รายการ
                    </div>
                </div>
            </div>

            <!-- Points KPI -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 bg-indigo-50 dark:bg-indigo-900/10 rounded-full w-24 h-24 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">เหรียญ (Points) ในระบบ</p>
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['total_points']) }}</h3>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium pb-px">
                        <a href="{{ route('backend.points.issues.index') }}">→ จัดการเติมเหรียญ</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- 📸 Feed & Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            
            <!-- Latest Users -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 flex flex-col">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700/60 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        สมาชิกล่าสุด
                    </h3>
                    <a href="{{ route('backend.users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">ดูทั้งหมด</a>
                </div>
                <div class="p-0 overflow-y-auto max-h-[400px]">
                    <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($latestUsers as $user)
                            <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <div class="flex items-center gap-x-4">
                                    <x-backend.avatar :user="$user" size="10"/>
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">
                                            <a href="{{ route('backend.users.show', $user->id) }}">
                                                {{ $user->getFullName() }}
                                            </a>
                                        </p>
                                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $user->email }}</p>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 shrink-0">
                                        <p class="text-xs leading-5 text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                        @if($user->is_account_verified)
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">ยืนยันแล้ว</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-6 py-8 text-center text-sm text-gray-500">ไม่มีข้อมูลผู้ใช้</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 flex flex-col">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700/60 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        ความเคลื่อนไหวระบบ
                    </h3>
                    <a href="{{ route('backend.logs.activity.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">บันทึกทั้งหมด</a>
                </div>
                <div class="p-6 overflow-y-auto max-h-[400px]">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($latestActivities as $key => $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if($key !== count($latestActivities) - 1)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            @if($activity->user)
                                                                {{ $activity->user->getFullName() }}
                                                            @else
                                                                System / Unknown
                                                            @endif
                                                        </span> 
                                                        <span class="text-gray-500">{{ $activity->getActivityTypeFormat() }}</span>
                                                        @if($activity->activity_value)
                                                            <span class="italic text-xs block text-gray-400 mt-0.5">"{{ Str::limit($activity->activity_value, 50) }}"</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                                    <time datetime="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center text-sm text-gray-500">ไม่มีข้อมูลความเคลื่อนไหว</div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
