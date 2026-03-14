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
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

        <!-- Footer / Version Info -->
        <div class="mt-8 text-center pb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-medium bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                v{{ config('app.version') }} ({{ config('app.build') }})
            </span>
        </div>
    </div>
    @if (isset($javascript))
        {{ $javascript }}
    @endif
</x-body>

</html>
