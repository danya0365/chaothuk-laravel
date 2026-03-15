<aside
    class="fixed inset-y-0 left-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 w-64 h-full flex flex-col z-30 transition-transform duration-300 ease-in-out transform lg:translate-x-0"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
>
    <!-- Branding -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700 px-4">
        <a href="{{ route('backend.index') }}" class="flex items-center gap-2">
            <x-application-logo class="w-8 h-8 fill-current text-indigo-600 dark:text-indigo-400" />
            <span class="text-xl font-bold text-gray-800 dark:text-gray-200">Chaothuk Admin</span>
        </a>
    </div>

    <!-- Scrollable Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('backend.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('backend.index') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.index') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
            </svg>
            ภาพรวมระบบ
        </a>

        <!-- User Management -->
        <a href="{{ route('backend.users.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.users.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.users.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            จัดการสมาชิก (Users)
        </a>

        <!-- Point Management Section -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ระบบจัดการพอยท์
        </h3>
        
        <a href="{{ route('backend.points.issues.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.points.issues.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.points.issues.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
            ตั้งค่าแคมเปญแจกพอยท์
        </a>

        <!-- Trust & Safety Section -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            Trust & Safety
        </h3>

        <a href="{{ route('backend.works.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.works.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.works.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            จัดการงานเช่า (Works)
        </a>

        <a href="{{ route('backend.recruits.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.recruits.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.recruits.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            จัดการประกาศจ้าง (Recruits)
        </a>

        <a href="{{ route('backend.posts.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.posts.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.posts.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            ชุมชนและรีวิว (Posts)
        </a>

        <a href="{{ route('backend.verifications.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.verifications.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.verifications.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
            ยืนยันตัวตน (KYC)
            @php
                $pendingCount = \App\Models\UserVerification::where('status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <!-- Dispute & Report Management Section -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ปัญหาและร้องเรียน
        </h3>

        <a href="{{ route('backend.reports.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.reports.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.reports.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            รายงานความไม่เหมาะสม
            @php
                $pendingReportsCount = \App\Models\UserReport::where('status', 'pending')->count();
            @endphp
            @if($pendingReportsCount > 0)
                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                    {{ $pendingReportsCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('backend.disputes.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.disputes.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.disputes.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0l.075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 1 .198-.471 1.575 1.575 0 1 0-2.228-2.228 3.818 3.818 0 0 0-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.35 15m.002 0h-.002" />
            </svg>
            ศูนย์ข้อพิพาท (Disputes)
            @php
                $openDisputesCount = \App\Models\Dispute::where('status', 'open')->count();
            @endphp
            @if($openDisputesCount > 0)
                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                    {{ $openDisputesCount }}
                </span>
            @endif
        </a>

        <!-- Access Control Section -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ระบบสิทธิการใช้งาน
        </h3>

        <!-- Access Control: Roles -->
        <a href="{{ route('backend.roles.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.roles.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.roles.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            ตั้งค่าบทบาท (Roles)
        </a>

        <!-- Access Control: Permissions -->
        <a href="{{ route('backend.permissions.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.permissions.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.permissions.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
            การตั้งค่าสิทธิ
        </a>
    </nav>
</aside>
