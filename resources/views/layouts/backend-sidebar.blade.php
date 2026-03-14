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
    </nav>
</aside>
