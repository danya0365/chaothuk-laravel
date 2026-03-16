<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chaothuk') }} - แพลตฟอร์มขนส่งและรับสมัครงาน</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700,900&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Dark Mode Init -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 md:h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-1.5 md:gap-2">
                        <span class="text-2xl md:text-3xl">🚛</span>
                        <span class="font-black text-xl md:text-2xl bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-amber-500 dark:from-orange-400 dark:to-amber-300 tracking-tight">
                            Chaothuk
                        </span>
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-2.5 md:space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-full text-sm p-2 md:p-2.5 transition">
                        <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>

                    @auth
                        <a href="{{ route('frontend.works') }}" class="hidden sm:inline-block text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-amber-400 transition">ค้นหางานเช่า</a>
                        
                        @if(auth()->user()->isCanAccessBackend())
                            <a href="{{ route('backend.index') }}" class="hidden sm:inline-block text-xs md:text-sm font-semibold text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition">จัดการระบบหลังบ้าน</a>
                        @endif

                        <a href="{{ route('frontend.home') }}" class="inline-flex items-center justify-center px-4 py-2 md:px-5 md:py-2.5 text-xs md:text-sm font-bold text-white transition-all duration-200 bg-orange-600 rounded-full hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 shadow-lg shadow-orange-600/30 dark:shadow-orange-500/20">
                            เข้าสู่แดชบอร์ด
                        </a>
                    @else
                        <a href="{{ route('frontend.auth.login') }}" class="hidden sm:inline-block text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-amber-400 transition">เข้าสู่ระบบ</a>
                        <a href="{{ route('frontend.auth.register') }}" class="inline-flex items-center justify-center px-4 py-2 md:px-5 md:py-2.5 text-xs md:text-sm font-bold text-white transition-all duration-200 bg-orange-600 rounded-full hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 shadow-lg shadow-orange-600/30 dark:shadow-orange-500/20">
                            เริ่มต้นใช้งานฟรี
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-14 md:pt-16 min-h-screen flex flex-col">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800 mt-auto transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 md:py-12 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start space-x-6 md:order-2">
                    <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-5 w-5 md:h-6 md:w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                <div class="mt-6 md:mt-0 md:order-1">
                    <p class="text-center md:text-left text-xs md:text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} Chaothuk. แพลตฟอร์มขนส่งเพื่อคนไทย. All rights reserved.
                    </p>
                    <div class="mt-2 flex justify-center md:justify-start text-[10px] md:text-xs text-gray-400 dark:text-gray-500 flex-wrap items-center gap-1.5">
                        <span>เวอร์ชัน {{ config('app.version', '1.0.0') }} ({{ config('app.build', 'local') }})</span>
                        @if(config('app.env') !== 'production')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] md:text-[10px] font-medium bg-yellow-100/50 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200/50 dark:border-yellow-800/50">
                                {{ strtoupper(config('app.env')) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts

    <!-- Dark Mode Toggle Script -->
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
</body>
</html>
