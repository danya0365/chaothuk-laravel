<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (isset($title))
        <title>{{ $title }}</title>
    @else
        <title>{{ config('app.name', 'Loan Application') }}</title>
    @endif

    <!-- Fonts -->

    <!-- Scripts -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<x-body>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
            @auth
                <div class="p-4">
                    <x-theme-toggle />
                </div>
            @endauth
        </main>

        <!-- Footer / Version Info -->
        <footer class="mt-auto py-4 px-6 text-center lg:text-right border-t border-gray-200 dark:border-gray-800">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                v{{ config('app.version') }} ({{ config('app.build') }})
            </span>
        </footer>

    </div>
    @if (isset($javascript))
        {{ $javascript }}
    @endif
</x-body>

</html>
