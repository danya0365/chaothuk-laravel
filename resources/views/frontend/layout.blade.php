<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Chaothuk' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css">
    <script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>
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
<nav class="hidden md:flex fixed left-0 top-0 h-full w-[70px] bg-gray-900 border-r border-gray-800 flex-col items-center pt-4 pb-4 z-50"
     x-data="{ moreOpen: false }">

    <a href="{{ route('frontend.home') }}" class="mb-3 text-xl font-black text-orange-400">C</a>

    @php
        // 5 main items always visible
        $mainNav = [
            ['icon'=>'🏠','label'=>'หน้าแรก','route'=>'frontend.home'],
            ['icon'=>'📦','label'=>'งาน','route'=>'frontend.works'],
            ['icon'=>'👷','label'=>'หา คน','route'=>'frontend.recruits'],
            ['icon'=>'🗺️','label'=>'แผนที่','route'=>'frontend.map'],
            ['icon'=>'🔍','label'=>'ค้นหา','route'=>'frontend.search'],
        ];
        // Extra items in "more" popup
        $moreNav = [
            ['icon'=>'🏷️','label'=>'หมวดหมู่','route'=>'frontend.categories','auth'=>false],
        ];
        $authMoreNav = [
            ['icon'=>'📅','label'=>'ปฏิทิน','route'=>'frontend.calendar'],
            ['icon'=>'📋','label'=>'การจอง','route'=>'frontend.bookings'],
            ['icon'=>'💬','label'=>'แชท','route'=>'frontend.messenger'],
            ['icon'=>'⭐','label'=>'โปรด','route'=>'frontend.favorites'],
            ['icon'=>'🎨','label'=>'ผลงาน','route'=>'frontend.portfolios'],
            ['icon'=>'🕐','label'=>'เซสชัน','route'=>'frontend.sessions'],
            ['icon'=>'🔔','label'=>'แจ้งเตือน','route'=>'frontend.notifications'],
        ];
    @endphp

    {{-- Main 5 nav items --}}
    @foreach($mainNav as $item)
        <a href="{{ route($item['route']) }}"
           class="flex flex-col items-center gap-0.5 w-full py-2.5 text-center transition
                  {{ request()->routeIs($item['route']) ? 'text-orange-400 bg-orange-500/10' : 'text-gray-500 hover:text-gray-200 hover:bg-gray-800' }}">
            <span class="text-lg leading-none">{{ $item['icon'] }}</span>
            <span class="text-[9px]">{{ $item['label'] }}</span>
        </a>
    @endforeach

    {{-- More button --}}
    <div class="relative w-full">
        <button @click="moreOpen = !moreOpen"
                :class="moreOpen ? 'text-orange-400 bg-orange-500/10' : 'text-gray-500 hover:text-gray-200 hover:bg-gray-800'"
                class="flex flex-col items-center gap-0.5 w-full py-2.5 text-center transition">
            <span class="text-lg leading-none">⋯</span>
            <span class="text-[9px]">เพิ่มเติม</span>
        </button>

        {{-- More popup --}}
        <div x-show="moreOpen" @click.away="moreOpen = false"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-2"
             class="absolute left-[70px] top-0 w-48 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl py-2 z-50"
             style="display: none;">

            <p class="px-3 py-1 text-[10px] text-gray-600 uppercase tracking-wider font-bold">เมนูเพิ่มเติม</p>

            @foreach($moreNav as $item)
                <a href="{{ route($item['route']) }}" @click="moreOpen = false"
                   class="flex items-center gap-2.5 px-3 py-2 transition text-sm
                          {{ request()->routeIs($item['route']) ? 'text-orange-400 bg-orange-500/10' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <span class="text-base w-6 text-center">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            @auth
                <div class="border-t border-gray-800 my-1"></div>
                @foreach($authMoreNav as $item)
                    <a href="{{ route($item['route']) }}" @click="moreOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 transition text-sm
                              {{ request()->routeIs($item['route']) ? 'text-orange-400 bg-orange-500/10' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span class="text-base w-6 text-center">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endauth
        </div>
    </div>

    <div class="flex-1"></div>

    {{-- Bottom: profile or login --}}
    @auth
        <a href="{{ route('frontend.profile') }}"
           class="flex flex-col items-center gap-0.5 w-full py-2.5 text-center transition
                  {{ request()->routeIs('frontend.profile') ? 'text-orange-400 bg-orange-500/10' : 'text-gray-500 hover:text-gray-200 hover:bg-gray-800' }}">
            <span class="text-lg">👤</span>
            <span class="text-[9px]">โปรไฟล์</span>
        </a>
    @else
        <a href="{{ route('login') }}"
           class="flex flex-col items-center gap-0.5 w-full py-2.5 text-center text-orange-400 hover:bg-orange-500/10 transition">
            <span class="text-lg">🔑</span>
            <span class="text-[9px]">เข้าสู่ระบบ</span>
        </a>
    @endauth
</nav>

{{-- ─── Main Content ────────────────────────────────────────────────── --}}
<main class="md:ml-[70px]">
    {{ $slot }}
</main>

{{-- ─── Bottom Nav (mobile) ────────────────────────────────────────── --}}
<div class="md:hidden" x-data="{ mobileMore: false }">
    {{-- Slide-up "More" panel --}}
    <div x-show="mobileMore" @click.self="mobileMore = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40"
         style="display: none;">
    </div>
    <div x-show="mobileMore"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed bottom-[56px] left-0 right-0 z-40 bg-gray-900 border-t border-gray-800 rounded-t-2xl shadow-2xl"
         style="display: none;">

        {{-- Handle bar --}}
        <div class="flex justify-center pt-2 pb-1">
            <div class="w-10 h-1 rounded-full bg-gray-700"></div>
        </div>

        <div class="grid grid-cols-4 gap-1 px-3 pb-4 pt-1">
            @php
                $mobileMoreItems = [
                    ['icon'=>'🗺️','label'=>'แผนที่','route'=>'frontend.map'],
                    ['icon'=>'🔍','label'=>'ค้นหา','route'=>'frontend.search'],
                    ['icon'=>'🏷️','label'=>'หมวดหมู่','route'=>'frontend.categories'],
                    ['icon'=>'👷','label'=>'หา คน','route'=>'frontend.recruits'],
                ];
                $mobileMoreAuth = [
                    ['icon'=>'📅','label'=>'ปฏิทิน','route'=>'frontend.calendar'],
                    ['icon'=>'📋','label'=>'การจอง','route'=>'frontend.bookings'],
                    ['icon'=>'🎨','label'=>'ผลงาน','route'=>'frontend.portfolios'],
                    ['icon'=>'🕐','label'=>'เซสชัน','route'=>'frontend.sessions'],
                    ['icon'=>'🔔','label'=>'แจ้งเตือน','route'=>'frontend.notifications'],
                ];
            @endphp
            @foreach($mobileMoreItems as $item)
                <a href="{{ route($item['route']) }}" @click="mobileMore = false"
                   class="flex flex-col items-center justify-center py-3 rounded-xl transition
                          {{ request()->routeIs($item['route']) ? 'text-orange-400 bg-orange-500/10' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                    <span class="text-xl mb-0.5">{{ $item['icon'] }}</span>
                    <span class="text-[10px]">{{ $item['label'] }}</span>
                </a>
            @endforeach
            @auth
                @foreach($mobileMoreAuth as $item)
                    <a href="{{ route($item['route']) }}" @click="mobileMore = false"
                       class="flex flex-col items-center justify-center py-3 rounded-xl transition
                              {{ request()->routeIs($item['route']) ? 'text-orange-400 bg-orange-500/10' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span class="text-xl mb-0.5">{{ $item['icon'] }}</span>
                        <span class="text-[10px]">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endauth
        </div>
    </div>

    {{-- Bottom bar --}}
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-gray-900/95 backdrop-blur border-t border-gray-800 flex">
        <a href="{{ route('frontend.home') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-center transition
                  {{ request()->routeIs('frontend.home') ? 'text-orange-400' : 'text-gray-500' }}">
            <span class="text-xl leading-none">🏠</span>
            <span class="text-[9px] font-medium">หน้าแรก</span>
        </a>
        <a href="{{ route('frontend.works') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-center transition
                  {{ request()->routeIs('frontend.works') ? 'text-orange-400' : 'text-gray-500' }}">
            <span class="text-xl leading-none">📦</span>
            <span class="text-[9px] font-medium">งาน</span>
        </a>
        @auth
        <a href="{{ route('frontend.favorites') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-center transition
                  {{ request()->routeIs('frontend.favorites') ? 'text-orange-400' : 'text-gray-500' }}">
            <span class="text-xl leading-none">⭐</span>
            <span class="text-[9px] font-medium">โปรด</span>
        </a>
        <a href="{{ route('frontend.messenger') }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-center transition
                  {{ request()->routeIs('frontend.messenger') ? 'text-orange-400' : 'text-gray-500' }}">
            <span class="text-xl leading-none">💬</span>
            <span class="text-[9px] font-medium">แชท</span>
        </a>
        @endauth
        {{-- More button --}}
        <button @click="mobileMore = !mobileMore"
                :class="mobileMore ? 'text-orange-400' : 'text-gray-500'"
                class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 text-center transition">
            <span class="text-xl leading-none" x-text="mobileMore ? '✕' : '⋯'">⋯</span>
            <span class="text-[9px] font-medium">เพิ่มเติม</span>
        </button>
    </nav>
</div>

@livewireScripts
@stack('scripts')
</body>
</html>
