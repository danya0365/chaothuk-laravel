<div class="max-w-6xl mx-auto px-4 py-6 space-y-10">

    {{-- Hero --}}
    <section class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-orange-600 via-orange-500 to-amber-400 p-8 md:p-12">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-black text-white mb-2">🚛 Chaothuk</h1>
            <p class="text-orange-100 text-lg mb-6">แพลตฟอร์มขนส่งและรับสมัครงาน สำหรับคนไทย</p>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('frontend.works') }}"
                   class="px-6 py-2.5 bg-white text-orange-600 font-bold rounded-full hover:bg-orange-50 transition shadow-lg">
                    ดูงานทั้งหมด
                </a>
                <a href="{{ route('frontend.recruits') }}"
                   class="px-6 py-2.5 bg-orange-900/30 text-white font-bold rounded-full hover:bg-orange-900/50 transition border border-white/20">
                    หาคน / รับสมัคร
                </a>
            </div>
        </div>
        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 text-[120px] flex items-center justify-center">🚛</div>
    </section>

    {{-- Banners --}}
    @if(count($banners) > 0)
        <section>
            <div class="flex gap-3 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-hide">
                @foreach($banners as $banner)
                    <a href="{{ $banner['external_url'] ?? '#' }}"
                       target="{{ $banner['external_url'] ? '_blank' : '_self' }}"
                       class="snap-start flex-shrink-0 w-full md:w-[calc(50%-6px)] rounded-2xl overflow-hidden relative group">
                        <img src="{{ $banner['image_url'] ?? 'https://picsum.photos/seed/b'.$banner['id'].'/1200/400' }}"
                             class="w-full h-40 md:h-48 object-cover group-hover:scale-105 transition duration-500" alt="{{ $banner['name'] }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <p class="text-white font-bold text-sm drop-shadow">{{ $banner['name'] }}</p>
                            @if($banner['is_pinned'] ?? false)
                                <span class="text-[10px] bg-orange-500/80 text-white px-2 py-0.5 rounded-full">📌 แนะนำ</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ⭐ Featured Works Carousel --}}
    @if(count($featuredWorks) > 0)
    <section x-data="{
        currentSlide: 0,
        total: {{ count($featuredWorks) }},
        autoplay: null,
        startAutoplay() {
            this.autoplay = setInterval(() => { this.next(); }, 4000);
        },
        stopAutoplay() {
            clearInterval(this.autoplay);
        },
        next() {
            this.currentSlide = (this.currentSlide + 1) % this.total;
        },
        prev() {
            this.currentSlide = (this.currentSlide - 1 + this.total) % this.total;
        }
    }" x-init="startAutoplay()" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">🔥 งานแนะนำ <span class="text-orange-400 text-xs bg-orange-500/20 px-2 py-0.5 rounded-full">Featured</span></h2>
        </div>

        <div class="relative overflow-hidden rounded-2xl">
            <div class="flex transition-transform duration-500 ease-in-out"
                 :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
                @foreach($featuredWorks as $fw)
                <div class="w-full flex-shrink-0">
                    <a href="{{ route('frontend.works.show', $fw['id']) }}"
                       class="block relative group">
                        <div class="aspect-[21/9] bg-gray-800 overflow-hidden">
                            <img src="{{ $fw['primary_image'] ?? 'https://picsum.photos/seed/'.$fw['id'].'/1200/500' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5">
                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">🔥 แนะนำ</span>
                                        @if($fw['type'])
                                            <span class="bg-white/20 text-white text-[10px] px-2 py-0.5 rounded-full backdrop-blur">{{ $fw['type'] }}</span>
                                        @endif
                                        @if($fw['province'])
                                            <span class="text-white/70 text-[11px]">📍 {{ $fw['province'] }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-white text-xl font-bold drop-shadow-lg">{{ $fw['title'] }}</h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-orange-400 text-lg font-black">฿{{ number_format($fw['price']) }}</span>
                                        @if($fw['rating'] > 0)
                                            <span class="text-yellow-400 text-sm">⭐ {{ number_format($fw['rating'], 1) }}</span>
                                        @endif
                                        @if($fw['likes'] > 0)
                                            <span class="text-white/60 text-sm">❤️ {{ $fw['likes'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($fw['author_name'])
                                <div class="flex items-center gap-2 bg-black/40 backdrop-blur rounded-full px-3 py-1.5">
                                    <x-avatar :src="$fw['author_avatar'] ?? null" :name="$fw['author_name'] ?? 'U'" size="xs" :border="false" />
                                    <span class="text-white text-xs font-medium">{{ $fw['author_name'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Nav arrows --}}
            @if(count($featuredWorks) > 1)
            <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 backdrop-blur text-white rounded-full flex items-center justify-center transition">‹</button>
            <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/50 hover:bg-black/70 backdrop-blur text-white rounded-full flex items-center justify-center transition">›</button>
            @endif
        </div>

        {{-- Dot indicators --}}
        @if(count($featuredWorks) > 1)
        <div class="flex justify-center gap-1.5 mt-3">
            @for($i = 0; $i < count($featuredWorks); $i++)
                <button @click="currentSlide = {{ $i }}"
                        class="w-2 h-2 rounded-full transition"
                        :class="currentSlide === {{ $i }} ? 'bg-orange-500 w-5' : 'bg-gray-700 hover:bg-gray-600'"></button>
            @endfor
        </div>
        @endif
    </section>
    @endif

    {{-- 🏆 Top Work per Province --}}
    @if(count($provinceTopWorks) > 0)
    <section x-data="{
        page: 0,
        perPage: 4,
        total: {{ count($provinceTopWorks) }},
        get maxPage() { return Math.ceil(this.total / this.perPage) - 1; },
        next() { if (this.page < this.maxPage) this.page++; },
        prev() { if (this.page > 0) this.page--; },
    }">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                🏆 งานเด่นประจำจังหวัด
                <span class="text-gray-500 text-xs font-normal">{{ now()->translatedFormat('F Y') }}</span>
            </h2>
            <div class="flex items-center gap-2">
                <button @click="prev()" :disabled="page === 0"
                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm flex items-center justify-center transition disabled:opacity-30 disabled:cursor-not-allowed">‹</button>
                <span class="text-gray-500 text-xs" x-text="(page+1)+'/'+Math.ceil(total/perPage)"></span>
                <button @click="next()" :disabled="page >= maxPage"
                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm flex items-center justify-center transition disabled:opacity-30 disabled:cursor-not-allowed">›</button>
            </div>
        </div>

        <div class="overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out"
                 :style="'transform: translateX(-' + (page * 100) + '%)'">
                {{-- Chunked into pages of 4 --}}
                @foreach(array_chunk($provinceTopWorks, 4) as $pageIdx => $chunk)
                <div class="w-full flex-shrink-0 grid grid-cols-2 md:grid-cols-4 gap-3 px-0.5">
                    @foreach($chunk as $tw)
                    <a href="{{ route('frontend.works.show', $tw['id']) }}"
                       class="group bg-gray-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-yellow-500/40 transition relative">
                        {{-- Province badge --}}
                        <div class="absolute top-2 left-2 z-10 bg-yellow-500/90 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            🏆 {{ $tw['province'] }}
                        </div>
                        <div class="aspect-video bg-gray-800 overflow-hidden">
                            <img src="{{ $tw['primary_image'] ?? 'https://picsum.photos/seed/'.$tw['id'].'/400/300' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                        </div>
                        <div class="p-3">
                            <p class="font-semibold text-sm text-white line-clamp-1 group-hover:text-yellow-400 transition">{{ $tw['title'] }}</p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-orange-400 font-bold text-sm">฿{{ number_format($tw['price']) }}</span>
                                <div class="flex items-center gap-1.5 text-[11px]">
                                    @if($tw['rating'] > 0)
                                        <span class="text-yellow-400">⭐ {{ number_format($tw['rating'], 1) }}</span>
                                    @endif
                                    @if($tw['bookings'] > 0)
                                        <span class="text-gray-500">📋 {{ $tw['bookings'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-1.5">
                                <div class="flex items-center gap-1.5">
                                    <x-avatar :src="$tw['author_avatar'] ?? null" :name="$tw['author_name'] ?? 'U'" size="w-4 h-4" :border="false" />
                                    <span class="text-gray-400 text-[11px] truncate max-w-[80px]">{{ $tw['author_name'] }}</span>
                                </div>
                                @if($tw['type'])
                                    <span class="text-gray-600 text-[10px]">{{ $tw['type'] }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>

        {{-- Dot indicators --}}
        @if(count($provinceTopWorks) > 4)
        <div class="flex justify-center gap-1.5 mt-3">
            @for($i = 0; $i < ceil(count($provinceTopWorks) / 4); $i++)
                <button @click="page = {{ $i }}"
                        class="w-2 h-2 rounded-full transition"
                        :class="page === {{ $i }} ? 'bg-yellow-500 w-5' : 'bg-gray-700 hover:bg-gray-600'"></button>
            @endfor
        </div>
        @endif
    </section>
    @endif

    {{-- Latest Works --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">📦 งานล่าสุด</h2>
            <a href="{{ route('frontend.works') }}" class="text-orange-400 text-sm hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($latestWorks as $work)
                <a href="{{ route('frontend.works.show', $work['id']) }}"
                   class="group bg-gray-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-orange-500/50 transition">
                    <div class="aspect-video bg-gray-800 overflow-hidden">
                        <img src="{{ $work['primary_image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/400/300' }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $work['title'] }}">
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-sm text-white line-clamp-1">{{ $work['title'] }}</p>
                        <p class="text-orange-400 font-bold text-sm">฿{{ number_format($work['price'] ?? 0) }}</p>
                        <p class="text-gray-500 text-xs mt-1">📍 {{ $work['province']['name_th'] ?? '-' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Latest Recruits --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">👷 รับสมัครงาน</h2>
            <a href="{{ route('frontend.recruits') }}" class="text-orange-400 text-sm hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($latestRecruits as $recruit)
                <a href="{{ route('frontend.recruits.show', $recruit['id']) }}"
                   class="flex gap-4 bg-gray-900 rounded-xl p-4 hover:ring-2 hover:ring-orange-500/50 transition group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                        <img src="{{ $recruit['primary_image'] ?? 'https://picsum.photos/seed/r'.$recruit['id'].'/200/200' }}"
                             class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-white line-clamp-1 group-hover:text-orange-400 transition">{{ $recruit['title'] }}</p>
                        <p class="text-green-400 font-bold text-sm">฿{{ number_format($recruit['budget'] ?? 0) }}/เดือน</p>
                        <p class="text-gray-500 text-xs">📍 {{ $recruit['province']['name_th'] ?? '-' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

</div>
