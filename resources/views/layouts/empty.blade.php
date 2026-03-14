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
    {{ $slot }}
    @if (isset($javascript))
        {{ $javascript }}
    @endif
</x-body>

</html>
