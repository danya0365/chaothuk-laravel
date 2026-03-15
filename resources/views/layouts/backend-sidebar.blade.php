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
