<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 relative z-30">
    <!-- Left side: Hamburger (Mobile only) & Header Title -->
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 lg:hidden mr-4">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        @if (isset($header))
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                {{ $header }}
            </h2>
        @else
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight hidden sm:block">
                Backend Dashboard
            </h2>
        @endif
    </div>

    <!-- Right side: Actions & Profile -->
    <div class="flex items-center space-x-4">
        <!-- Dark Mode Toggle -->
        <div>
            <x-theme-toggle />
        </div>

        <!-- Profile Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                    <div>{{ Auth::user()->name ?? 'Admin' }}</div>

                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <!-- Authentication -->
                <form method="POST" action="{{ route('frontend.auth.logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('frontend.auth.logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('ออกจากระบบ') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
