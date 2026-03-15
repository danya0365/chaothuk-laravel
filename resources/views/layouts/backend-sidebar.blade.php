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

        <!-- 📊 เมนูหลัก (Main Navigation) -->
        <a href="{{ route('backend.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('backend.index') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.index') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
            </svg>
            ภาพรวมระบบ
        </a>

        <!-- 👥 ผู้ใช้งานและผู้ดูแล (Users & Access) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ผู้ใช้งานและผู้ดูแล
        </h3>
        
        <a href="{{ route('backend.users.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.users.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.users.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            จัดการสมาชิก (Users)
        </a>

        <a href="{{ route('backend.verifications.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.verifications.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.verifications.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
            สถานะยืนยันตัวตน (KYC)
            @php
                $pendingCount = \App\Models\UserVerification::where('status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('backend.sessions.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.sessions.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.sessions.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
            <span class="truncate">สถานะเข้าสู่ระบบ (Sessions)</span>
        </a>

        <a href="{{ route('backend.roles.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.roles.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.roles.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            ตั้งค่าบทบาท (Roles)
        </a>

        <a href="{{ route('backend.permissions.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.permissions.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.permissions.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
            ตั้งค่าสิทธิ (Permissions)
        </a>

        <!-- 💼 ธุรกรรมและแคตตาล็อก (Works & Catalogs) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ธุรกรรมและแคตตาล็อก
        </h3>

        <a href="{{ route('backend.works.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.works.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
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

        <a href="{{ route('backend.categories.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.categories.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.categories.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            หมวดหมู่ (Categories)
        </a>

        <a href="{{ route('backend.work-types.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.work-types.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.work-types.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            ประเภทงาน (Work Types)
        </a>

        <a href="{{ route('backend.portfolios.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.portfolios.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.portfolios.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            แฟ้มผลงาน (Portfolios)
        </a>

        <!-- 🛡️ ความปลอดภัยและข้อพิพาท (Trust & Safety) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ความปลอดภัยและข้อพิพาท
        </h3>

        <a href="{{ route('backend.reports.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.reports.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.reports.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            ปัญหาและร้องเรียน
            @php
                $pendingReportsCount = \App\Models\UserReport::where('status', 'pending')->count();
            @endphp
            @if($pendingReportsCount > 0)
                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                    {{ $pendingReportsCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('backend.disputes.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.disputes.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
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

        <a href="{{ route('backend.posts.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.posts.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.posts.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            ชุมชนสังคม (Posts)
        </a>

        <a href="{{ route('backend.reputations.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.reputations.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.reputations.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            ชื่อเสียงและรีวิว (Reputation)
        </a>

        <!-- 💰 การเงินและพอยท์ (Finance & Rewards) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            การเงินและพอยท์
        </h3>
        
        <a href="{{ route('backend.points.issues.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 mb-2 {{ request()->routeIs('backend.points.issues.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.points.issues.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
            แคมเปญแจกพอยท์
        </a>

        <!-- 📢 สื่อสาร (Communication) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ระบบสื่อสาร
        </h3>

        <a href="{{ route('backend.messenger.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.messenger.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.messenger.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
            </svg>
            แชททั้งหมด (Messenger)
        </a>

        <a href="{{ route('backend.notifications.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.notifications.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.notifications.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            การแจ้งเตือน (Notifications)
        </a>

        <!-- ⚙️ ตั้งค่าและข้อมูลหลัก (System & Master Data) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ตั้งค่าและข้อมูลหลัก
        </h3>

        <a href="{{ route('backend.settings.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.settings.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.settings.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            การตั้งค่าแพลตฟอร์ม
        </a>

        <a href="{{ route('backend.banners.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.banners.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.banners.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            แบนเนอร์ (Banners)
        </a>

        <a href="{{ route('backend.locations.provinces.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-2 {{ request()->routeIs('backend.locations.provinces.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.locations.provinces.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            จังหวัด/พื้นที่ (Provinces)
        </a>

        <!-- 🛠️ ตรวจสอบระบบ (System Logs) -->
        <h3 class="px-2 mt-8 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
            ตรวจสอบระบบ
        </h3>

        <a href="{{ route('backend.logs.activity.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-2 {{ request()->routeIs('backend.logs.activity.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.logs.activity.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            ประวัติการใช้งาน (Activity Log)
        </a>

        <a href="{{ route('backend.logs.crons.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 {{ request()->routeIs('backend.logs.crons.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.logs.crons.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            การทำงานเบื้องหลัง (Cron Logs)
        </a>

        <a href="{{ route('backend.logs.work-sessions.index') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md mt-1 mb-8 {{ request()->routeIs('backend.logs.work-sessions.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('backend.logs.work-sessions.*') ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-400 dark:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            ประวัติเซสชันงาน (Work Sessions)
        </a>

    </nav>
</aside>
