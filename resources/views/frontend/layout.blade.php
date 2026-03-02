<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Chaothuk' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { padding-bottom: 72px; }
        @media(min-width:768px) { body { padding-bottom: 0; padding-left: 70px; } }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen antialiased">

{{-- ─── Top Bar ─────────────────────────────────────────────────────── --}}
<header class="sticky top-0 z-40 bg-gray-900/95 backdrop-blur border-b border-gray-800 md:ml-[70px]">
    <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
        <a href="{{ route('frontend.home') }}" class="flex items-center gap-2 font-bold text-lg text-orange-400">
            🚛 <span class="hidden sm:inline">Chaothuk</span>
        </a>

        <div class="flex-1 max-w-sm mx-4">
            <a href="{{ route('frontend.search') }}"
               class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 rounded-full px-4 py-1.5 text-sm text-gray-400 transition">
                🔍 <span>ค้นหางาน...</span>
            </a>
        </div>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('frontend.notifications') }}"
                   class="relative p-2 text-gray-400 hover:text-white transition">
                    🔔
                </a>
                <a href="{{ route('frontend.profile') }}"
                   class="w-8 h-8 rounded-full overflow-hidden ring-2 ring-orange-500/50">
                    <img src="{{ auth()->user()?->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()?->name ?? 'U') }}"
                         class="w-full h-full object-cover" alt="profile">
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-1.5 bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold rounded-full transition">
                    เข้าสู่ระบบ
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ─── Side Nav (desktop) ─────────────────────────────────────────── --}}
<nav class="hidden md:flex fixed left-0 top-0 h-full w-[70px] bg-gray-900 border-r border-gray-800 flex-col items-center pt-4 pb-6 gap-1 z-50">
    @php
        $navItems = [
            ['icon'=>'🏠','label'=>'หน้าแรก','route'=>'frontend.home'],
            ['icon'=>'📦','label'=>'งาน','route'=>'frontend.works'],
            ['icon'=>'👷','label'=>'หา คน','route'=>'frontend.recruits'],
            ['icon'=>'🔍','label'=>'ค้นหา','route'=>'frontend.search'],
        ];
    @endphp
    <a href="{{ route('frontend.home') }}" class="mb-4 text-xl font-black text-orange-400">C</a>

    @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="flex flex-col items-center gap-0.5 w-full py-3 text-center transition
                  {{ request()->routeIs($item['route']) ? 'text-orange-400 bg-orange-500/10' : 'text-gray-500 hover:text-gray-200 hover:bg-gray-800' }}">
            <span class="text-xl leading-none">{{ $item['icon'] }}</span>
            <span class="text-[10px]">{{ $item['label'] }}</span>
        </a>
    @endforeach

    <div class="flex-1"></div>

    @auth
        <a href="{{ route('frontend.notifications') }}"
           class="flex flex-col items-center gap-0.5 w-full py-3 text-center text-gray-500 hover:text-gray-200 hover:bg-gray-800 transition">
            <span class="text-xl">🔔</span>
            <span class="text-[10px]">แจ้งเตือน</span>
        </a>
        <a href="{{ route('frontend.profile') }}"
           class="flex flex-col items-center gap-0.5 w-full py-3 text-center text-gray-500 hover:text-gray-200 hover:bg-gray-800 transition">
            <span class="text-xl">👤</span>
            <span class="text-[10px]">โปรไฟล์</span>
        </a>
    @else
        <a href="{{ route('login') }}"
           class="flex flex-col items-center gap-0.5 w-full py-3 text-center text-orange-400 hover:bg-orange-500/10 transition">
            <span class="text-xl">🔑</span>
            <span class="text-[10px]">เข้าสู่ระบบ</span>
        </a>
    @endauth
</nav>

{{-- ─── Main Content ────────────────────────────────────────────────── --}}
<main class="md:ml-[70px]">
    {{ $slot }}
</main>

{{-- ─── Bottom Nav (mobile) ────────────────────────────────────────── --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-gray-900/95 backdrop-blur border-t border-gray-800 flex">
    @php
        $bottomItems = [
            ['icon'=>'🏠','label'=>'หน้าแรก','route'=>'frontend.home'],
            ['icon'=>'📦','label'=>'งาน','route'=>'frontend.works'],
            ['icon'=>'👷','label'=>'รับสมัคร','route'=>'frontend.recruits'],
            ['icon'=>'🔍','label'=>'ค้นหา','route'=>'frontend.search'],
            auth()->check()
                ? ['icon'=>'👤','label'=>'โปรไฟล์','route'=>'frontend.profile']
                : ['icon'=>'🔑','label'=>'เข้าสู่ระบบ','route'=>'login'],
        ];
    @endphp
    @foreach($bottomItems as $item)
        <a href="{{ route($item['route']) }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-center transition
                  {{ request()->routeIs($item['route']) ? 'text-orange-400' : 'text-gray-500 hover:text-gray-200' }}">
            <span class="text-xl leading-none">{{ $item['icon'] }}</span>
            <span class="text-[9px] font-medium">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>

@livewireScripts
</body>
</html>
