<div class="max-w-6xl mx-auto px-3 sm:px-4 py-4 md:py-6" x-data="{ viewMode: 'grid' }">

    {{-- Header with Tabs + Create Button --}}
    <div class="flex items-center justify-between mb-3 md:mb-5">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-white">📦 งาน</h1>
        </div>
        @auth
        <a href="{{ route('frontend.works.create') }}"
           class="px-3 py-1.5 md:px-5 md:py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg md:rounded-xl text-xs md:text-sm transition flex items-center gap-1 md:gap-1.5">
            ＋ สร้างงานใหม่
        </a>
        @endauth
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-3 md:mb-5 bg-gray-900 rounded-xl p-1 w-fit">
        <button wire:click="$set('tab', 'all')"
                class="px-3 py-1.5 md:px-5 md:py-2 rounded-lg text-xs md:text-sm font-semibold transition
                    {{ $tab === 'all' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
            📦 งานทั้งหมด
        </button>
        @auth
        <button wire:click="$set('tab', 'my')"
                class="px-3 py-1.5 md:px-5 md:py-2 rounded-lg text-xs md:text-sm font-semibold transition flex items-center gap-1 md:gap-1.5
                    {{ $tab === 'my' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
            👤 งานของฉัน
            @if($myWorksCount > 0)
                <span class="bg-white/20 text-[9px] md:text-[10px] px-1.5 py-0.5 rounded-full font-bold">{{ $myWorksCount }}</span>
            @endif
        </button>
        @endauth
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row flex-wrap gap-2 md:gap-3 mb-3 md:mb-4">
        <input wire:model.live.debounce.400ms="search" type="text"
               placeholder="🔍 ค้นหางาน..."
               class="flex-1 w-full sm:min-w-[200px] bg-gray-800 border border-gray-700 rounded-lg md:rounded-xl px-3 py-2 md:px-4 md:py-2.5 text-xs md:text-sm text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 transition">

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-row sm:gap-3 w-full sm:w-auto">
            <select wire:model.live="provinceId"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg md:rounded-xl px-2 py-2 md:px-3 md:py-2.5 text-xs md:text-sm text-gray-300 focus:outline-none focus:border-orange-500 transition">
                <option value="">📍 ทุกจังหวัด</option>
                @foreach($provinces as $p)
                    <option value="{{ $p->id }}">{{ $p->name_th }}</option>
                @endforeach
            </select>

            <select wire:model.live="workTypeId"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg md:rounded-xl px-2 py-2 md:px-3 md:py-2.5 text-xs md:text-sm text-gray-300 focus:outline-none focus:border-orange-500 transition">
                <option value="">🚛 ทุกประเภท</option>
                @foreach($workTypes as $wt)
                    <option value="{{ $wt->id }}">{{ $wt->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-row sm:gap-3 w-full sm:w-auto">
            <select wire:model.live="sortBy"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg md:rounded-xl px-2 py-2 md:px-3 md:py-2.5 text-xs md:text-sm text-gray-300 focus:outline-none focus:border-orange-500 transition">
                <option value="latest">ล่าสุด</option>
                <option value="popular">ยอดนิยม</option>
                <option value="rating">คะแนนสูงสุด</option>
                <option value="price_asc">ราคาต่ำ → สูง</option>
                <option value="price_desc">ราคาสูง → ต่ำ</option>
                <option value="distance" x-show="$wire.userLat" disabled>📍 ระยะทางใกล้สุด</option>
            </select>

            <div x-data="{
                handleLocationPicked(e) {
                    @this.set('userLat', e.detail.lat);
                    @this.set('userLng', e.detail.lng);
                    @this.set('sortBy', 'distance');
                }
            }" @location-picked.window="handleLocationPicked" class="flex gap-2 w-full sm:w-auto">
                
                <x-location-picker
                    wire:model.lat="userLat"
                    wire:model.lng="userLng"
                    label="📍 ใกล้ฉัน"
                    class="h-[36px] md:h-[46px] w-full text-xs md:text-sm {{ $sortBy === 'distance' ? 'bg-orange-500/20 text-orange-400 border-orange-500/50 ring-1 ring-orange-500/50' : '' }}" />
                    
                @if($userLat && $userLng)
                    <button type="button" wire:click="$set('userLat', null); $set('userLng', null); $set('sortBy', 'latest')"
                            class="h-[36px] md:h-[46px] px-3 md:px-4 bg-gray-800 border border-gray-700 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/50 text-gray-400 rounded-lg md:rounded-xl transition flex items-center justify-center font-bold"
                            title="ยกเลิกระยะทาง">
                        ✕
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- View Toggle + Count --}}
    <div class="flex items-center justify-between mb-3 md:mb-4">
        <p class="text-gray-400 text-xs md:text-sm">
            {{ $works->total() }} งาน
            @if($tab === 'my')
                <span class="text-orange-400 text-[10px] md:text-xs">(ของฉัน)</span>
            @endif
        </p>
        <div class="flex gap-1 bg-gray-800 rounded-lg p-1">
            <button @click="viewMode = 'grid'"
                    :class="viewMode === 'grid' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white'"
                    class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-md text-[10px] md:text-xs font-semibold transition">
                ☷ รายการ
            </button>
            <button @click="viewMode = 'map'; $nextTick(() => window.dispatchEvent(new Event('init-browse-map')))"
                    :class="viewMode === 'map' ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white'"
                    class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-md text-[10px] md:text-xs font-semibold transition">
                🗺️ แผนที่
            </button>
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading class="text-center py-8 text-gray-400">กำลังโหลด...</div>

    {{-- Grid View --}}
    <div wire:loading.remove x-show="viewMode === 'grid'">
        @if($works->isEmpty())
            <div class="text-center py-10 md:py-20">
                <div class="text-5xl md:text-6xl mb-3 md:mb-4">{{ $tab === 'my' ? '📭' : '📭' }}</div>
                @if($tab === 'my')
                    <p class="text-gray-400 text-sm md:text-base mb-3 md:mb-4">คุณยังไม่มีงาน</p>
                    <a href="{{ route('frontend.works.create') }}"
                       class="inline-block px-5 py-2 md:px-6 md:py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl text-xs md:text-sm transition">
                        ＋ สร้างงานแรกของคุณ
                    </a>
                @else
                    <p class="text-gray-400 text-sm md:text-base">ไม่พบผลลัพธ์</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-4 mb-6 md:mb-8">
                @foreach($works as $work)
                    <div class="group bg-gray-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-orange-500/40 transition relative">
                        {{-- Owner badge --}}
                        @if($tab === 'my' || (auth()->check() && auth()->id() === $work->author_id))
                            <div class="absolute top-2 right-2 z-10 flex gap-1">
                                @php
                                    $statusColor = match($work->work_status) {
                                        'stand-by' => 'bg-green-500',
                                        'busy' => 'bg-yellow-500',
                                        'close' => 'bg-red-500',
                                        default => 'bg-gray-500',
                                    };
                                @endphp
                                <span class="{{ $statusColor }} w-2.5 h-2.5 rounded-full"></span>
                            </div>
                        @endif

                        <a href="{{ route('frontend.works.show', $work->id) }}">
                            <div class="aspect-video bg-gray-800 overflow-hidden">
                                <img src="{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/400/300' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $work->title }}">
                            </div>
                        </a>
                        <div class="p-2 md:p-3">
                            <a href="{{ route('frontend.works.show', $work->id) }}"
                               class="font-semibold text-xs md:text-sm text-white hover:text-orange-400 transition line-clamp-2 block mb-0.5 md:mb-1">
                                {{ $work->title }}
                            </a>
                            <p class="text-orange-400 font-bold text-sm md:text-base">฿{{ number_format($work->price) }}</p>
                            <div class="flex items-center justify-between mt-1 md:mt-2">
                                <div class="flex flex-col gap-0.5">
                                    <p class="text-gray-500 text-[10px] md:text-xs">📍 {{ $work->province?->name_th ?? '-' }}</p>
                                    @if(isset($work->distance))
                                        <p class="text-orange-400 text-[9px] md:text-[10px] font-semibold">
                                            @if($work->distance < 1)
                                                ({{ number_format($work->distance * 1000) }} เมตร)
                                            @else
                                                ({{ number_format($work->distance, 1) }} กม.)
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 md:gap-2">
                                    @if($work->avg_review_rating > 0)
                                        <span class="text-yellow-400 text-[10px] md:text-xs">⭐ {{ number_format($work->avg_review_rating,1) }}</span>
                                    @endif
                                    <button wire:click="toggleLike({{ $work->id }})"
                                            class="text-[10px] md:text-xs transition {{ in_array($work->id, $likedIds) ? 'text-red-400' : 'text-gray-600 hover:text-red-400' }}">
                                        ❤️ {{ $work->like_count }}
                                    </button>
                                </div>
                            </div>

                            {{-- Owner quick actions --}}
                            @if($tab === 'my' && auth()->check() && auth()->id() === $work->author_id)
                            <div class="flex items-center gap-1.5 md:gap-2 mt-2 pt-2 border-t border-gray-800">
                                <a href="{{ route('frontend.works.edit', $work->id) }}"
                                   class="flex-1 text-center text-[10px] md:text-xs py-1.5 md:py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition">
                                    ✏️ แก้ไข
                                </a>
                                <a href="{{ route('frontend.works.bookings', $work->id) }}"
                                   class="flex-1 text-center text-[10px] md:text-xs py-1.5 md:py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition">
                                    📋 จอง
                                </a>
                                <button wire:click="deleteWork({{ $work->id }})"
                                        wire:confirm="ลบงานนี้?"
                                        class="text-[10px] md:text-xs py-1.5 md:py-2 px-2 md:px-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition flex-shrink-0">
                                    🗑
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $works->links() }}
        @endif
    </div>

    {{-- Map View --}}
    <div x-show="viewMode === 'map'" x-cloak>
        <div wire:ignore id="browse-map" class="w-full rounded-2xl overflow-hidden" style="height: 65vh;"></div>
    </div>

</div>

@push('scripts')
<script>
(function() {
    let browseMapInitialized = false;

    window.addEventListener('init-browse-map', function() {
        if (browseMapInitialized) return;
        browseMapInitialized = true;

        const mapEl = document.getElementById('browse-map');
        if (!mapEl) return;

        const map = new maplibregl.Map({
            container: 'browse-map',
            style: {
                version: 8,
                sources: {
                    'osm-tiles': {
                        type: 'raster',
                        tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
                        tileSize: 256,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }
                },
                layers: [{
                    id: 'osm-tiles-layer',
                    type: 'raster',
                    source: 'osm-tiles',
                    minzoom: 0,
                    maxzoom: 19
                }]
            },
            center: [100.5018, 13.7563],
            zoom: 6
        });

        map.addControl(new maplibregl.NavigationControl(), 'top-right');

        const works = @json($works->items());
        const bounds = new maplibregl.LngLatBounds();
        let hasPoints = false;

        works.forEach(function(work) {
            if (!work.latitude || !work.longitude) return;
            hasPoints = true;

            const markerEl = document.createElement('div');
            markerEl.innerHTML = '<svg width="28" height="38" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 0C8.07 0 0 8.07 0 18c0 13.5 18 30 18 30s18-16.5 18-30C36 8.07 27.93 0 18 0z" fill="#F97316"/><circle cx="18" cy="18" r="8" fill="white"/><circle cx="18" cy="18" r="4" fill="#F97316"/></svg>';
            markerEl.style.cursor = 'pointer';

            const popup = new maplibregl.Popup({ offset: 25, closeButton: false })
                .setHTML('<div style="padding:8px;font-family:sans-serif;max-width:200px;">' +
                    '<a href="/frontend/works/' + work.id + '" style="font-weight:700;font-size:13px;color:#111;text-decoration:none;">' + work.title + '</a>' +
                    '<p style="color:#666;font-size:11px;margin:4px 0 0;">\ud83d\udccd ' + (work.province ? work.province.name_th : '-') + '</p>' +
                    '<p style="color:#F97316;font-weight:700;font-size:13px;margin:4px 0 0;">฿' + Number(work.price || 0).toLocaleString() + '</p>' +
                '</div>');

            new maplibregl.Marker({ element: markerEl })
                .setLngLat([work.longitude, work.latitude])
                .setPopup(popup)
                .addTo(map);

            bounds.extend([work.longitude, work.latitude]);
        });

        if (hasPoints) {
            map.fitBounds(bounds, { padding: 50, maxZoom: 12 });
        }
    });
})();
</script>
@endpush
