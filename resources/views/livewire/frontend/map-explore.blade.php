<div class="relative" x-data="{ filter: 'all', showList: false }">

    {{-- Top Filter Bar --}}
    <div class="absolute top-3 left-3 right-3 z-10 flex items-center gap-2 flex-wrap">
        <div class="flex gap-1 bg-gray-900/90 backdrop-blur rounded-xl p-1 shadow-lg">
            <button @click="filter = 'all'; window.filterMarkers('all')"
                    :class="filter === 'all' ? 'bg-orange-500 text-white' : 'text-gray-300 hover:text-white'"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                ทั้งหมด
            </button>
            <button @click="filter = 'work'; window.filterMarkers('work')"
                    :class="filter === 'work' ? 'bg-orange-500 text-white' : 'text-gray-300 hover:text-white'"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                📦 งาน
            </button>
            <button @click="filter = 'recruit'; window.filterMarkers('recruit')"
                    :class="filter === 'recruit' ? 'bg-purple-500 text-white' : 'text-gray-300 hover:text-white'"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                👷 รับสมัคร
            </button>
        </div>

        {{-- Toggle list sidebar --}}
        <button @click="showList = !showList"
                class="bg-gray-900/90 backdrop-blur text-gray-300 hover:text-white px-3 py-1.5 rounded-xl text-xs font-semibold shadow-lg transition">
            <span x-text="showList ? '✕ ซ่อนรายการ' : '☰ แสดงรายการ'"></span>
        </button>

        {{-- Count --}}
        <div class="bg-gray-900/90 backdrop-blur text-gray-400 px-3 py-1.5 rounded-xl text-xs shadow-lg">
            📍 <span class="text-white font-bold">{{ count($works) + count($recruits) }}</span> ตำแหน่ง
        </div>
    </div>

    {{-- Map --}}
    <div id="explore-map" class="w-full" style="height: calc(100vh - 72px);"></div>

    {{-- Side List Panel --}}
    <div x-show="showList" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="absolute top-14 right-3 bottom-3 w-72 bg-gray-900/95 backdrop-blur rounded-2xl shadow-2xl z-10 flex flex-col overflow-hidden">
        <div class="p-3 border-b border-gray-800">
            <p class="text-white font-bold text-sm">📋 รายการทั้งหมด</p>
        </div>
        <div class="flex-1 overflow-y-auto p-2 space-y-1.5" id="side-list">
            @foreach($works as $w)
                <a href="{{ route('frontend.works.show', $w['id']) }}" data-kind="work"
                   class="side-item flex gap-2 p-2 rounded-xl hover:bg-gray-800 transition group">
                    <img src="{{ $w['image'] ?? 'https://picsum.photos/seed/'.$w['id'].'/80/80' }}"
                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-semibold truncate group-hover:text-orange-400">{{ $w['title'] }}</p>
                        <p class="text-orange-400 text-[11px] font-bold">฿{{ number_format($w['price']) }}</p>
                        <p class="text-gray-500 text-[10px]">📍 {{ $w['province'] ?? '-' }}</p>
                    </div>
                </a>
            @endforeach
            @foreach($recruits as $r)
                <a href="{{ route('frontend.recruits.show', $r['id']) }}" data-kind="recruit"
                   class="side-item flex gap-2 p-2 rounded-xl hover:bg-gray-800 transition group">
                    <img src="{{ $r['image'] ?? 'https://picsum.photos/seed/r'.$r['id'].'/80/80' }}"
                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-semibold truncate group-hover:text-purple-400">{{ $r['title'] }}</p>
                        <p class="text-purple-400 text-[11px] font-bold">฿{{ number_format($r['price']) }}</p>
                        <p class="text-gray-500 text-[10px]">📍 {{ $r['province'] ?? '-' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Legend --}}
    <div class="absolute bottom-4 left-3 bg-gray-900/90 backdrop-blur rounded-xl px-3 py-2 shadow-lg z-10 flex items-center gap-3 text-[11px]">
        <span class="flex items-center gap-1">
            <span class="w-3 h-3 rounded-full bg-orange-500 inline-block"></span>
            <span class="text-gray-300">งาน</span>
        </span>
        <span class="flex items-center gap-1">
            <span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>
            <span class="text-gray-300">รับสมัคร</span>
        </span>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allItems = @json(array_merge($works, $recruits));
    const markers = [];

    const map = new maplibregl.Map({
        container: 'explore-map',
        style: {
            version: 8,
            sources: {
                'osm': {
                    type: 'raster',
                    tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
                    tileSize: 256,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }
            },
            layers: [{
                id: 'osm-layer',
                type: 'raster',
                source: 'osm',
                minzoom: 0,
                maxzoom: 19
            }]
        },
        center: [100.5018, 13.7563],
        zoom: 6
    });

    map.addControl(new maplibregl.NavigationControl(), 'top-right');
    map.addControl(new maplibregl.GeolocateControl({ trackUserLocation: true }), 'top-right');

    const bounds = new maplibregl.LngLatBounds();
    let hasPoints = false;

    function makeSvg(color) {
        return '<svg width="28" height="38" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg">' +
            '<path d="M18 0C8.07 0 0 8.07 0 18c0 13.5 18 30 18 30s18-16.5 18-30C36 8.07 27.93 0 18 0z" fill="' + color + '"/>' +
            '<circle cx="18" cy="18" r="8" fill="white"/>' +
            '<circle cx="18" cy="18" r="4" fill="' + color + '"/></svg>';
    }

    allItems.forEach(function(item) {
        if (!item.lat || !item.lng) return;
        hasPoints = true;

        const isWork = item.kind === 'work';
        const color = isWork ? '#F97316' : '#A855F7';
        const route = isWork ? '/frontend/works/' : '/frontend/recruits/';

        const el = document.createElement('div');
        el.innerHTML = makeSvg(color);
        el.style.cursor = 'pointer';
        el.dataset.kind = item.kind;

        const ratingHtml = item.rating > 0 ? '<span style="color:#EAB308;font-size:11px;">⭐ ' + Number(item.rating).toFixed(1) + '</span>' : '';

        const popup = new maplibregl.Popup({ offset: 25, closeButton: false, maxWidth: '220px' })
            .setHTML(
                '<div style="padding:8px;font-family:sans-serif;">' +
                    (item.image ? '<img src="' + item.image + '" style="width:100%;height:80px;object-fit:cover;border-radius:8px;margin-bottom:6px;" />' : '') +
                    '<a href="' + route + item.id + '" style="font-weight:700;font-size:13px;color:#111;text-decoration:none;display:block;">' + item.title + '</a>' +
                    '<p style="color:#666;font-size:11px;margin:3px 0 0;">📍 ' + (item.province || '-') + '</p>' +
                    '<div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">' +
                        '<span style="color:' + color + ';font-weight:700;font-size:13px;">฿' + Number(item.price || 0).toLocaleString() + '</span>' +
                        ratingHtml +
                    '</div>' +
                '</div>'
            );

        const marker = new maplibregl.Marker({ element: el })
            .setLngLat([item.lng, item.lat])
            .setPopup(popup)
            .addTo(map);

        markers.push({ marker, el, kind: item.kind });
        bounds.extend([item.lng, item.lat]);
    });

    if (hasPoints) {
        map.fitBounds(bounds, { padding: 60, maxZoom: 12 });
    }

    // Filter function
    window.filterMarkers = function(filter) {
        markers.forEach(function(m) {
            if (filter === 'all' || m.kind === filter) {
                m.el.style.display = '';
            } else {
                m.el.style.display = 'none';
                m.marker.getPopup()?.remove();
            }
        });

        // Filter side list too
        document.querySelectorAll('.side-item').forEach(function(el) {
            if (filter === 'all' || el.dataset.kind === filter) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    };
});
</script>
@endpush
