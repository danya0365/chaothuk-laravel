<div class="max-w-3xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    @if(!$portfolio)
        <p class="text-gray-400 text-center py-10 md:py-20">ไม่พบผลงานนี้</p>
    @else

    {{-- Back + Title --}}
    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
        <button onclick="history.back()" class="text-xs md:text-sm text-gray-400 hover:text-white transition">← กลับ</button>
        <h1 class="text-lg md:text-xl font-bold text-white truncate">{{ $portfolio['title'] }}</h1>
    </div>

    {{-- Image Gallery --}}
    @php $images = $portfolio['images'] ?? []; @endphp
    @if(count($images) > 0)
    <div x-data="{ activeImage: @js(image_url($images[0])), lightbox: false }"
         class="space-y-2 md:space-y-3 mb-4 md:mb-6">
        {{-- Main image --}}
        <div class="rounded-xl md:rounded-2xl overflow-hidden bg-gray-800 aspect-video cursor-pointer" @click="lightbox = true">
            <img :src="activeImage" class="w-full h-full object-cover" alt="{{ $portfolio['title'] }}">
        </div>

        {{-- Thumbnails --}}
        @if(count($images) > 1)
        <div class="flex gap-2 overflow-x-auto pb-1">
            @foreach($images as $idx => $img)
            <button @click="activeImage = @js(image_url($img))"
                    class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden ring-2 transition"
                    :class="activeImage === @js(image_url($img)) ? 'ring-orange-500' : 'ring-transparent hover:ring-gray-600'">
                <img src="{{ image_url($img) }}" class="w-full h-full object-cover" alt="">
            </button>
            @endforeach
        </div>
        @endif

        {{-- Lightbox --}}
        <div x-show="lightbox" x-transition.opacity
             @click="lightbox = false"
             class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4 cursor-pointer">
            <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-lg" alt="">
            <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 transition">✕</button>
        </div>
    </div>
    @else
    <div class="rounded-xl md:rounded-2xl bg-gray-800 aspect-video flex items-center justify-center text-gray-600 text-4xl md:text-6xl mb-4 md:mb-6">🖼</div>
    @endif

    {{-- Info Card --}}
    <div class="bg-gray-900 rounded-xl md:rounded-2xl p-4 md:p-5 space-y-3 md:space-y-4 mb-3 md:mb-4">
        <div>
            <h2 class="text-lg md:text-xl font-bold text-white">{{ $portfolio['title'] }}</h2>
            @if(isset($portfolio['work_type']))
                <span class="inline-block mt-0.5 md:mt-1 text-[10px] md:text-xs font-semibold text-orange-400 bg-orange-500/10 px-2 md:px-2.5 py-0.5 rounded-full">
                    {{ $portfolio['work_type']['title'] ?? $portfolio['work_type']['name'] ?? '' }}
                </span>
            @endif
        </div>

        @if($portfolio['description'])
        <div class="pt-2.5 md:pt-3 border-t border-gray-800">
            <h3 class="text-xs md:text-sm font-semibold text-gray-400 mb-1.5 md:mb-2 flex items-center gap-1">📝 รายละเอียด</h3>
            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap text-xs md:text-sm">{{ $portfolio['description'] }}</p>
        </div>
        @endif

        <div class="pt-2 md:pt-3 border-t border-gray-800 text-[10px] md:text-xs text-gray-600">
            เพิ่มเมื่อ {{ \Illuminate\Support\Carbon::parse($portfolio['created_at'])->diffForHumans() }}
        </div>
    </div>

    <a href="{{ route('frontend.profile', $portfolio['user']['id']) }}"
       class="bg-gray-900 rounded-xl md:rounded-2xl p-3 md:p-4 flex items-center gap-3 md:gap-4 hover:ring-2 hover:ring-orange-500/40 transition block group mb-3 md:mb-4">
        <x-avatar :src="$portfolio['user']['profile_image'] ?? null" :name="$portfolio['user']['name'] ?? 'U'" size="w-10 h-10 md:w-12 md:h-12" :border="false" class="ring-2 ring-orange-500/30" />
        <div class="flex-1 min-w-0">
            <p class="font-bold text-white text-sm md:text-base group-hover:text-orange-400 transition">{{ $portfolio['user']['name'] }}</p>
            <p class="text-gray-500 text-[10px] md:text-xs">ดูผลงานทั้งหมด</p>
        </div>
        <span class="text-gray-500 text-xs md:text-sm group-hover:text-orange-400 transition flex-shrink-0">ดูโปรไฟล์ →</span>
    </a>
    @endif

    {{-- Other portfolios by same user --}}
    @if(count($userPortfolios) > 0)
    <div class="bg-gray-900 rounded-xl md:rounded-2xl p-4 md:p-5">
        <h3 class="font-bold text-white mb-2 md:mb-3 flex items-center gap-1.5 md:gap-2 text-sm md:text-base">🎨 ผลงานอื่นๆ ของช่างท่านนี้</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 md:gap-3">
            @foreach($userPortfolios as $p)
            <a href="{{ route('frontend.portfolios.show', $p['id']) }}"
               class="rounded-xl overflow-hidden bg-gray-800 group hover:ring-1 hover:ring-orange-500/30 transition">
                @php $pImages = $p['images'] ?? []; @endphp
                @if(count($pImages) > 0)
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ image_url($pImages[0]) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                    </div>
                @else
                    <div class="aspect-square flex items-center justify-center text-gray-600 text-3xl">🖼</div>
                @endif
                <div class="p-2 md:p-2.5">
                    <p class="text-white text-[10px] md:text-xs font-semibold truncate">{{ $p['title'] }}</p>
                    @if(isset($p['work_type']))
                        <span class="text-[9px] md:text-[10px] text-orange-400">{{ $p['work_type']['title'] ?? '' }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
