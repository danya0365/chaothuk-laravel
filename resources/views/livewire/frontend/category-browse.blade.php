<div class="max-w-5xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    <h1 class="text-xl md:text-2xl font-bold text-white mb-4 md:mb-6">🏷️ หมวดหมู่</h1>

    @if($selectedCategory)
        {{-- Back + Selected Category --}}
        <div class="mb-4 md:mb-6">
            <button wire:click="clearCategory"
                    class="flex items-center gap-1.5 md:gap-2 text-gray-400 hover:text-white text-xs md:text-sm mb-2 md:mb-3 transition">
                ← กลับไปรายการหมวดหมู่
            </button>
            <div class="bg-gray-900 rounded-xl p-3 md:p-4 flex items-center gap-3 md:gap-4">
                @if($selectedCategory['image_url'] ?? null)
                    <img src="{{ $selectedCategory['image_url'] }}"
                         class="w-14 h-14 rounded-lg object-cover" alt="">
                @else
                    <div class="w-14 h-14 rounded-lg bg-orange-500/20 flex items-center justify-center text-2xl">🏷️</div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $selectedCategory['name'] }}</h2>
                    @if($selectedCategory['description'] ?? null)
                        <p class="text-gray-400 text-sm">{{ $selectedCategory['description'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Works in Category --}}
        <div class="mb-6 md:mb-8">
            <h3 class="font-bold text-white text-sm md:text-base mb-2 md:mb-3">📦 งาน ({{ count($works ?? []) }})</h3>
            @if(empty($works))
                <p class="text-gray-500 text-xs md:text-sm text-center py-4 md:py-6">ยังไม่มีงานในหมวดหมู่นี้</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3">
                    @foreach($works as $work)
                        <a href="{{ route('frontend.works.show', $work['id']) }}"
                           class="bg-gray-900 rounded-xl overflow-hidden hover:ring-1 hover:ring-orange-500/50 transition group">
                            <div class="aspect-video bg-gray-800 overflow-hidden">
                                <img src="{{ $work['primary_image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/400/225' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                            </div>
                            <div class="p-3">
                                <h4 class="font-semibold text-white text-sm truncate">{{ $work['title'] }}</h4>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-orange-400 font-bold text-sm">฿{{ number_format($work['price']) }}</span>
                                    <span class="text-gray-500 text-xs">📍 {{ $work['province'] ?? '-' }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recruits in Category --}}
        <div>
            <h3 class="font-bold text-white text-sm md:text-base mb-2 md:mb-3">👷 รับสมัคร ({{ count($recruits ?? []) }})</h3>
            @if(empty($recruits))
                <p class="text-gray-500 text-xs md:text-sm text-center py-4 md:py-6">ยังไม่มีประกาศรับสมัครในหมวดหมู่นี้</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3">
                    @foreach($recruits as $recruit)
                        <a href="{{ route('frontend.recruits.show', $recruit['id']) }}"
                           class="bg-gray-900 rounded-xl overflow-hidden hover:ring-1 hover:ring-purple-500/50 transition group">
                            <div class="aspect-video bg-gray-800 overflow-hidden">
                                <img src="{{ $recruit['primary_image'] ?? 'https://picsum.photos/seed/r'.$recruit['id'].'/400/225' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                            </div>
                            <div class="p-3">
                                <h4 class="font-semibold text-white text-sm truncate">{{ $recruit['title'] }}</h4>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-purple-400 font-bold text-sm">฿{{ number_format($recruit['budget']) }}</span>
                                    <span class="text-gray-500 text-xs">📍 {{ $recruit['province'] ?? '-' }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    @else
        {{-- Category Grid --}}
        @if(empty($categories))
            <div class="text-center py-10 md:py-16">
                <div class="text-4xl md:text-5xl mb-2 md:mb-3">🏷️</div>
                <p class="text-gray-400 font-medium text-sm md:text-base">ยังไม่มีหมวดหมู่</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 md:gap-3">
                @foreach($categories as $cat)
                    <button wire:click="selectCategory({{ $cat['id'] }})"
                            class="bg-gray-900 rounded-xl p-3 md:p-4 text-center hover:ring-1 hover:ring-orange-500/50 transition group">
                        @if($cat['image_url'] ?? null)
                            <img src="{{ $cat['image_url'] }}"
                                 class="w-12 h-12 md:w-16 md:h-16 mx-auto rounded-lg object-cover mb-2 md:mb-3 group-hover:scale-110 transition duration-300" alt="">
                        @else
                            <div class="w-12 h-12 md:w-16 md:h-16 mx-auto rounded-lg bg-orange-500/10 flex items-center justify-center text-2xl md:text-3xl mb-2 md:mb-3 group-hover:scale-110 transition duration-300">
                                🏷️
                            </div>
                        @endif
                        <p class="font-semibold text-white text-xs md:text-sm">{{ $cat['name'] }}</p>
                        @if($cat['description'] ?? null)
                            <p class="text-gray-500 text-[10px] md:text-xs mt-0.5 md:mt-1 line-clamp-2">{{ $cat['description'] }}</p>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif
    @endif

</div>
