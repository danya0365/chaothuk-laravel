<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (isset($title))
        <title>{{ $title }} | {{ config('app.name', 'Chaothuk') }}</title>
    @else
        <title>{{ config('app.name', 'Chaothuk Backend') }}</title>
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=mali:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-full overflow-hidden flex flex-col">
    <!-- Theme Script Injection for FOUC protection -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    
    <div class="h-full bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white flex overflow-hidden font-sans" x-data="{ sidebarOpen: false }">

        <!-- Sidebar -->
        @include('layouts.backend-sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300 lg:pl-64">

            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80 z-20 lg:hidden"
                 @click="sidebarOpen = false"></div>

            <!-- Top Header -->
            @include('layouts.backend-header')

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-4 sm:p-6">
                <!-- Session Alerts -->
                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-4 px-6 text-center lg:flex lg:justify-between lg:items-center border-t border-gray-200 dark:border-gray-800 shrink-0 bg-white dark:bg-gray-800">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 lg:mb-0 text-left">
                    เวอร์ชัน {{ config('app.version', '1.0.0') }} ({{ config('app.build', 'local') }})
                    @if(config('app.env') !== 'production')
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700">
                            {{ strtoupper(config('app.env')) }}
                        </span>
                    @endif
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                    Livewire v3 Backend Component
                </span>
            </footer>
        </div>
    </div>
</body>
</html>
