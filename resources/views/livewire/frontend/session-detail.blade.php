<div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-4 md:mb-6">
        <a href="{{ route('frontend.sessions') }}" class="text-gray-400 hover:text-white transition text-xs md:text-sm">← กลับ</a>
        <h1 class="text-xl md:text-2xl font-bold text-white">🕐 เซสชัน #{{ $id }}</h1>
        @php
            $statusBadge = match($session->status) {
                'active'    => 'bg-green-500/20 text-green-400',
                'paused'    => 'bg-yellow-500/20 text-yellow-400',
                'completed' => 'bg-blue-500/20 text-blue-400',
                'cancelled' => 'bg-red-500/20 text-red-400',
                default     => 'bg-gray-700 text-gray-400',
            };
            $statusLabel = match($session->status) {
                'active'    => '🟢 กำลังทำ',
                'paused'    => '⏸ หยุดชั่วคราว',
                'completed' => '✅ เสร็จสิ้น',
                'cancelled' => '❌ ยกเลิก',
                default     => $session->status,
            };
        @endphp
        <span class="text-[10px] md:text-xs font-bold px-2.5 md:px-3 py-0.5 md:py-1 rounded-full {{ $statusBadge }}">{{ $statusLabel }}</span>
    </div>

    {{-- Flash --}}
    @if($flashMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm mb-3 md:mb-4">
            {{ $flashMessage }}
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         REAL-TIME MAP — FULL WIDTH, PREMIUM TRACKING UI
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-gray-900 rounded-2xl overflow-hidden mb-4 md:mb-6 relative" wire:ignore>

        {{-- Map Container --}}
        <div id="session-map" class="w-full h-[300px] md:h-[420px]"></div>

        {{-- Live Stats Overlay (top-left) --}}
        @if($session->status === 'active')
        <div class="absolute top-2 left-2 md:top-3 md:left-3 z-10 bg-gray-950/90 backdrop-blur-md rounded-xl px-3 py-2 md:px-4 md:py-3 shadow-2xl border border-gray-800/50">
            <div class="flex items-center gap-1.5 md:gap-2 mb-1.5 md:mb-2">
                <span class="relative flex h-2.5 w-2.5 md:h-3 md:w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 md:h-3 md:w-3 bg-green-500"></span>
                </span>
                <span class="text-green-400 text-[10px] md:text-xs font-bold tracking-wide uppercase">Live Tracking</span>
            </div>
            <div class="grid grid-cols-3 gap-2 md:gap-3 text-center">
                <div>
                    <div class="text-white text-base md:text-lg font-mono font-bold" id="stat-elapsed">00:00</div>
                    <div class="text-gray-500 text-[9px] md:text-[10px]">เวลา</div>
                </div>
                <div>
                    <div class="text-white text-base md:text-lg font-mono font-bold" id="stat-distance">0 m</div>
                    <div class="text-gray-500 text-[9px] md:text-[10px]">ระยะทาง</div>
                </div>
                <div>
                    <div class="text-white text-base md:text-lg font-mono font-bold" id="stat-points">{{ count($locationLogs) }}</div>
                    <div class="text-gray-500 text-[9px] md:text-[10px]">จุด GPS</div>
                </div>
            </div>
        </div>
        @endif

        {{-- GPS Control (top-right) --}}
        @if($session->status === 'active')
        <div class="absolute top-2 right-12 md:top-3 md:right-14 z-10">
            <button id="btn-gps-toggle"
                    onclick="window._tracker?.toggleTracking()"
                    class="bg-green-500 hover:bg-green-400 text-white px-2 py-1.5 md:px-3 md:py-2 rounded-xl text-[10px] md:text-xs font-bold shadow-lg transition flex items-center gap-1.5">
                <span id="gps-icon" class="relative flex h-2 w-2 md:h-2.5 md:w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 md:h-2.5 md:w-2.5 bg-white"></span>
                </span>
                <span id="gps-label">📡 GPS ON</span>
            </button>
        </div>
        @endif

        {{-- Current Speed Overlay (bottom-left) --}}
        @if($session->status === 'active')
        <div class="absolute bottom-2 left-2 md:bottom-3 md:left-3 z-10 bg-gray-950/90 backdrop-blur-md rounded-xl px-2 py-1.5 md:px-3 md:py-2 shadow-lg border border-gray-800/50">
            <div class="text-white text-xl md:text-2xl font-mono font-black border-b border-gray-800 pb-0.5 mb-0.5 text-center" id="stat-speed">0</div>
            <div class="text-gray-500 text-[9px] md:text-[10px] text-center">km/h</div>
        </div>
        @endif

        {{-- Legend (bottom-right) --}}
        <div class="absolute bottom-2 right-2 md:bottom-3 md:right-3 z-10 bg-gray-950/90 backdrop-blur-md rounded-xl px-2 py-1.5 md:px-3 md:py-2 shadow-lg border border-gray-800/50 flex items-center gap-2 md:gap-3 text-[9px] md:text-[10px]">
            <span class="flex items-center gap-1"><span class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full bg-green-500"></span><span class="text-gray-400">เริ่ม</span></span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full bg-orange-500"></span><span class="text-gray-400">เส้นทาง</span></span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-full bg-blue-500 ring-2 ring-blue-400/50"></span><span class="text-gray-400">ปัจจุบัน</span></span>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 mb-4 md:mb-6">
        {{-- Session Info --}}
        <div class="bg-gray-900 rounded-xl p-3 md:p-4 space-y-2 md:space-y-3">
            <h2 class="font-semibold text-white text-xs md:text-sm">📋 ข้อมูลเซสชัน</h2>
            <div class="space-y-1.5 md:space-y-2 text-xs md:text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">ประเภท</span>
                    <span class="text-white">{{ class_basename($session->sessionable_type) === 'Work' ? '📦 งาน' : '👷 รับสมัคร' }} #{{ $session->sessionable_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">เริ่ม</span>
                    <span class="text-white">{{ $session->started_at?->format('d/m/Y H:i') }}</span>
                </div>
                @if($session->ended_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">สิ้นสุด</span>
                    <span class="text-white">{{ $session->ended_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">ระยะเวลา</span>
                    <span class="text-white font-bold">
                        @if($session->total_duration_minutes > 0)
                            {{ floor($session->total_duration_minutes / 60) }} ชม. {{ $session->total_duration_minutes % 60 }} น.
                        @elseif($session->status === 'active')
                            <span class="text-green-400 animate-pulse">● กำลังนับเวลา...</span>
                        @else
                            -
                        @endif
                    </span>
                </div>
                @if($session->price_agreed)
                <div class="flex justify-between">
                    <span class="text-gray-500">ราคาตกลง</span>
                    <span class="text-orange-400 font-bold text-base md:text-lg">฿{{ number_format($session->price_agreed) }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- People --}}
        <div class="bg-gray-900 rounded-xl p-3 md:p-4 space-y-2 md:space-y-3">
            <h2 class="font-semibold text-white text-xs md:text-sm">👥 ผู้เกี่ยวข้อง</h2>
            <div class="space-y-2 md:space-y-3">
                <div class="flex items-center gap-2 md:gap-3">
                    <x-avatar :src="$session->worker?->profile_image" :name="$session->worker?->name ?? 'W'" size="md" :border="false" class="shrink-0" />
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs md:text-sm font-medium truncate">{{ $session->worker?->name ?? '-' }}</p>
                        <p class="text-gray-500 text-[10px] md:text-xs truncate">👷 ผู้ให้บริการ {{ $isWorker ? '(คุณ)' : '' }}</p>
                    </div>
                    <span class="text-[9px] md:text-xs px-1.5 md:px-2 py-0.5 rounded-full whitespace-nowrap {{ $session->worker_confirm === 'confirmed' ? 'bg-green-500/20 text-green-400' : 'bg-gray-700 text-gray-500' }}">
                        {{ $session->worker_confirm === 'confirmed' ? '✓ ยืนยัน' : '… รอยืนยัน' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 md:gap-3">
                    <x-avatar :src="$session->customer?->profile_image" :name="$session->customer?->name ?? 'C'" size="md" :border="false" class="shrink-0" />
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs md:text-sm font-medium truncate">{{ $session->customer?->name ?? '-' }}</p>
                        <p class="text-gray-500 text-[10px] md:text-xs truncate">👤 ลูกค้า {{ $isCustomer ? '(คุณ)' : '' }}</p>
                    </div>
                    <span class="text-[9px] md:text-xs px-1.5 md:px-2 py-0.5 rounded-full whitespace-nowrap {{ $session->customer_confirm === 'confirmed' ? 'bg-green-500/20 text-green-400' : 'bg-gray-700 text-gray-500' }}">
                        {{ $session->customer_confirm === 'confirmed' ? '✓ ยืนยัน' : '… รอยืนยัน' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row flex-wrap gap-2 mb-4 md:mb-6">
        @if(in_array($session->status, ['active', 'paused']))
            @if($session->status === 'active')
                <button wire:click="pauseSession"
                        class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2 bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 rounded-lg text-xs md:text-sm font-semibold hover:bg-yellow-500/30 transition text-center justify-center flex items-center">
                    ⏸ หยุดชั่วคราว
                </button>
            @else
                <button wire:click="resumeSession"
                        class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2 bg-green-500/20 text-green-400 border border-green-500/30 rounded-lg text-xs md:text-sm font-semibold hover:bg-green-500/30 transition text-center justify-center flex items-center">
                    ▶️ เริ่มต่อ
                </button>
            @endif
            <button wire:click="stopSession" wire:confirm="ยืนยันหยุดงาน?"
                    class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2 bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-xs md:text-sm font-semibold hover:bg-red-500/30 transition text-center justify-center flex items-center">
                ⏹ หยุดงาน
            </button>
        @endif

        @if($session->status === 'completed')
            @php $myConfirmField = $isWorker ? 'worker_confirm' : 'customer_confirm'; @endphp
            @if($session->$myConfirmField !== 'confirmed')
                <button wire:click="confirmSession"
                        class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2 bg-green-500 text-white rounded-lg text-xs md:text-sm font-bold hover:bg-green-400 transition text-center justify-center flex items-center">
                    ✅ ยืนยันเซสชัน
                </button>
            @else
                <span class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2 bg-green-500/10 text-green-400 rounded-lg text-xs md:text-sm text-center flex items-center justify-center">✅ คุณยืนยันแล้ว</span>
            @endif
        @endif
    </div>

    {{-- Location Log Table --}}
    @if(count($locationLogs) > 0)
    <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-gray-800">
        <div class="px-3 md:px-4 py-2 md:py-3 border-b border-gray-800 flex items-center justify-between bg-gray-900/50">
            <h2 class="font-semibold text-white text-xs md:text-sm">📊 GPS Logs ({{ count($locationLogs) }})</h2>
            <button wire:click="refreshLocations" class="text-[10px] md:text-xs text-gray-500 hover:text-white transition px-2 py-1 rounded bg-gray-800 hover:bg-gray-700">🔄 รีเฟรช</button>
        </div>
        <div class="overflow-x-auto max-h-64 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-gray-950 shadow-sm z-10">
                    <tr class="text-gray-400 border-b border-gray-800 text-[9px] md:text-xs tracking-wider uppercase">
                        <th class="px-3 md:px-4 py-2 font-medium">#</th>
                        <th class="px-3 md:px-4 py-2 font-medium">เวลา</th>
                        <th class="px-3 md:px-4 py-2 font-medium">Lat</th>
                        <th class="px-3 md:px-4 py-2 font-medium">Lng</th>
                        <th class="px-3 md:px-4 py-2 font-medium">ความแม่นยำ</th>
                        <th class="px-3 md:px-4 py-2 font-medium">ความเร็ว</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-[10px] md:text-xs font-mono">
                    @foreach($locationLogs as $i => $log)
                        <tr class="hover:bg-gray-800/50 transition-colors {{ $loop->last ? 'text-green-400 bg-green-400/5' : 'text-gray-300' }}">
                            <td class="px-3 md:px-4 py-2.5 whitespace-nowrap">{{ $i + 1 }}</td>
                            <td class="px-3 md:px-4 py-2.5 whitespace-nowrap">{{ $log['recorded_at'] }}</td>
                            <td class="px-3 md:px-4 py-2.5">{{ number_format($log['lat'], 5) }}</td>
                            <td class="px-3 md:px-4 py-2.5">{{ number_format($log['lng'], 5) }}</td>
                            <td class="px-3 md:px-4 py-2.5 whitespace-nowrap">{{ $log['accuracy'] ?? '-' }}m</td>
                            <td class="px-3 md:px-4 py-2.5 whitespace-nowrap">{{ $log['speed'] ? number_format($log['speed'], 1) . ' km/h' : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
/* Current location marker pulse ring */
.marker-current {
    width: 24px; height: 24px; position: relative; cursor: pointer;
}
.marker-current::before {
    content: ''; position: absolute; inset: -6px;
    border-radius: 50%; background: rgba(59,130,246,0.25);
    animation: markerPulse 2s ease-out infinite;
}
.marker-current::after {
    content: ''; position: absolute; inset: 0;
    border-radius: 50%; background: #3B82F6;
    border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4);
}
.marker-current .arrow {
    position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
    width: 0; height: 0;
    border-left: 6px solid transparent; border-right: 6px solid transparent;
    border-bottom: 10px solid #3B82F6; z-index: 1;
    transition: transform 0.5s ease;
}
.marker-start {
    width: 18px; height: 18px; border-radius: 50%;
    background: #22C55E; border: 3px solid white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3); cursor: pointer;
}
/* Accuracy circle */
.accuracy-ring {
    border-radius: 50%; border: 2px solid rgba(59,130,246,0.4);
    background: rgba(59,130,246,0.08); pointer-events: none;
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
}
@keyframes markerPulse {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(2.5); opacity: 0; }
}
</style>
<script>
(function() {
    // ─── CONFIG ───
    const SESSION_ID     = {{ $id }};
    const IS_ACTIVE      = @json($session->status === 'active');
    const STARTED_AT     = @json($session->started_at?->timestamp);
    const SEND_INTERVAL  = 10000;  // 10s between GPS sends
    const INITIAL_LOGS   = @json($locationLogs);

    // ─── STATE ───
    let map, routeSource, currentMarker, startMarker, accuracyEl;
    let allPoints = INITIAL_LOGS.map(l => ({ lat: l.lat, lng: l.lng, accuracy: l.accuracy, speed: l.speed, heading: null, time: l.recorded_at }));
    let tracking  = false;
    let watchId   = null;
    let sendTimer = null;
    let elapsedTimer = null;
    let totalDistance = 0;

    // ─── UTILITIES ───
    function toRad(d) { return d * Math.PI / 180; }
    function haversine(a, b) {
        const R = 6371e3;
        const dLat = toRad(b.lat - a.lat), dLng = toRad(b.lng - a.lng);
        const x = Math.sin(dLat/2)**2 + Math.cos(toRad(a.lat)) * Math.cos(toRad(b.lat)) * Math.sin(dLng/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(x), Math.sqrt(1-x));
    }
    function fmtDist(m) {
        return m >= 1000 ? (m/1000).toFixed(1) + ' km' : Math.round(m) + ' m';
    }
    function fmtElapsed(sec) {
        const h = Math.floor(sec / 3600), m = Math.floor((sec % 3600) / 60), s = sec % 60;
        return (h > 0 ? h + ':' : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    }

    // ─── COMPUTE INITIAL DISTANCE ───
    for (let i = 1; i < allPoints.length; i++) {
        totalDistance += haversine(allPoints[i-1], allPoints[i]);
    }

    // ─── INIT MAP ───
    function initMap() {
        if (map || !document.getElementById('session-map')) return;

        const center = allPoints.length > 0
            ? [allPoints[allPoints.length-1].lng, allPoints[allPoints.length-1].lat]
            : [100.5018, 13.7563];

        map = new maplibregl.Map({
            container: 'session-map',
            style: {
                version: 8,
                sources: {
                    'carto': {
                        type: 'raster',
                        tiles: ['https://basemaps.cartocdn.com/dark_all/{z}/{x}/{y}@2x.png'],
                        tileSize: 256,
                        attribution: '&copy; <a href="https://carto.com">CARTO</a> &copy; <a href="https://www.openstreetmap.org">OSM</a>'
                    }
                },
                layers: [{ id: 'carto-layer', type: 'raster', source: 'carto', minzoom: 0, maxzoom: 20 }]
            },
            center: center,
            zoom: allPoints.length > 0 ? 16 : 6
        });

        map.addControl(new maplibregl.NavigationControl(), 'top-right');

        map.on('load', () => {
            // Route source
            map.addSource('route', {
                type: 'geojson',
                data: makeRouteLine()
            });
            routeSource = map.getSource('route');

            // Route glow
            map.addLayer({
                id: 'route-glow', type: 'line', source: 'route',
                paint: { 'line-color': '#F97316', 'line-width': 10, 'line-opacity': 0.2, 'line-blur': 4 }
            });
            // Route main
            map.addLayer({
                id: 'route-line', type: 'line', source: 'route',
                layout: { 'line-cap': 'round', 'line-join': 'round' },
                paint: { 'line-color': '#F97316', 'line-width': 4, 'line-opacity': 0.9 }
            });
            // Route dots
            map.addLayer({
                id: 'route-dots', type: 'circle', source: 'route',
                paint: { 'circle-radius': 3, 'circle-color': '#F97316', 'circle-opacity': 0.6 },
                filter: ['==', '$type', 'Point']
            });

            // Place markers
            if (allPoints.length > 0) {
                placeStartMarker(allPoints[0]);
                placeCurrentMarker(allPoints[allPoints.length - 1]);
                fitToRoute();
            }

            // Start tracking if active
            if (IS_ACTIVE) {
                startTracking();
                startElapsedTimer();
            }
        });
    }

    function makeRouteLine() {
        const coords = allPoints.map(p => [p.lng, p.lat]);
        return {
            type: 'FeatureCollection',
            features: [
                { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: coords } },
                ...allPoints.map(p => ({ type: 'Feature', properties: {}, geometry: { type: 'Point', coordinates: [p.lng, p.lat] } }))
            ]
        };
    }

    function placeStartMarker(p) {
        if (startMarker) startMarker.remove();
        const el = document.createElement('div');
        el.className = 'marker-start';
        startMarker = new maplibregl.Marker({ element: el })
            .setLngLat([p.lng, p.lat])
            .setPopup(new maplibregl.Popup({ offset: 12, closeButton: false }).setHTML(
                '<div style="padding:6px;font-size:12px;font-family:sans-serif;"><b>🟢 จุดเริ่มต้น</b><br><span style="color:#999;">' + (p.time || '') + '</span></div>'
            ))
            .addTo(map);
    }

    function placeCurrentMarker(p) {
        if (currentMarker) currentMarker.remove();

        const container = document.createElement('div');
        container.className = 'marker-current';
        const arrow = document.createElement('div');
        arrow.className = 'arrow';
        arrow.id = 'heading-arrow';
        if (p.heading) arrow.style.transform = 'translateX(-50%) rotate(' + p.heading + 'deg)';
        container.appendChild(arrow);

        currentMarker = new maplibregl.Marker({ element: container })
            .setLngLat([p.lng, p.lat])
            .setPopup(new maplibregl.Popup({ offset: 16, closeButton: false }).setHTML(
                '<div style="padding:6px;font-size:12px;font-family:sans-serif;"><b>📍 ตำแหน่งปัจจุบัน</b><br>' +
                '<span style="color:#999;">Lat: ' + p.lat.toFixed(5) + '</span><br>' +
                '<span style="color:#999;">Lng: ' + p.lng.toFixed(5) + '</span>' +
                (p.accuracy ? '<br><span style="color:#999;">±' + Math.round(p.accuracy) + 'm</span>' : '') +
                '</div>'
            ))
            .addTo(map);
    }

    function fitToRoute() {
        if (allPoints.length === 0) return;
        if (allPoints.length === 1) {
            map.flyTo({ center: [allPoints[0].lng, allPoints[0].lat], zoom: 16, duration: 1000 });
            return;
        }
        const bounds = new maplibregl.LngLatBounds();
        allPoints.forEach(p => bounds.extend([p.lng, p.lat]));
        map.fitBounds(bounds, { padding: 60, maxZoom: 17, duration: 1000 });
    }

    // ─── UPDATE MAP (called when new point arrives) ───
    function addPoint(lat, lng, accuracy, speed, heading) {
        const newPt = { lat, lng, accuracy, speed, heading, time: new Date().toLocaleTimeString('th-TH') };

        // Calculate distance
        if (allPoints.length > 0) {
            const last = allPoints[allPoints.length - 1];
            const dist = haversine(last, newPt);
            if (dist < 1) return; // Skip if <1m (noise)
            totalDistance += dist;
        }

        allPoints.push(newPt);

        // Update route line
        if (routeSource) routeSource.setData(makeRouteLine());

        // Update/place markers
        if (allPoints.length === 1) placeStartMarker(allPoints[0]);

        // Smoothly move current marker
        if (currentMarker) {
            currentMarker.setLngLat([lng, lat]);
            // Update heading arrow
            const arrow = document.getElementById('heading-arrow');
            if (arrow && heading) arrow.style.transform = 'translateX(-50%) rotate(' + heading + 'deg)';
        } else {
            placeCurrentMarker(newPt);
        }

        // Pan map to follow
        if (map) {
            map.easeTo({ center: [lng, lat], duration: 800 });
        }

        // Update stats
        updateStats(speed);

        // Save to server via Livewire
        @this.call('logLocation', lat, lng, accuracy ? Math.round(accuracy) : null, speed ? Math.round(speed * 10) / 10 : null, heading || null);
    }

    function updateStats(speed) {
        const el = document.getElementById('stat-distance');
        if (el) el.textContent = fmtDist(totalDistance);

        const pts = document.getElementById('stat-points');
        if (pts) pts.textContent = allPoints.length;

        const spd = document.getElementById('stat-speed');
        if (spd) spd.textContent = speed ? Math.round(speed) : '0';
    }

    // ─── ELAPSED TIMER ───
    function startElapsedTimer() {
        if (!STARTED_AT) return;
        function tick() {
            const elapsed = Math.floor(Date.now() / 1000) - STARTED_AT;
            const el = document.getElementById('stat-elapsed');
            if (el) el.textContent = fmtElapsed(elapsed);
        }
        tick();
        elapsedTimer = setInterval(tick, 1000);
    }

    // ─── GPS TRACKING ───
    function startTracking() {
        if (tracking || !navigator.geolocation) return;
        tracking = true;
        updateGpsButton(true);

        // Continuous watch
        watchId = navigator.geolocation.watchPosition(
            (pos) => {
                const { latitude, longitude, accuracy, speed, heading } = pos.coords;
                const speedKmh = speed ? speed * 3.6 : 0;
                addPoint(latitude, longitude, accuracy, speedKmh, heading);
            },
            (err) => console.warn('GPS watch error:', err.message),
            { enableHighAccuracy: true, maximumAge: 3000, timeout: 15000 }
        );
    }

    function stopTracking() {
        tracking = false;
        updateGpsButton(false);
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
    }

    function updateGpsButton(on) {
        const btn = document.getElementById('btn-gps-toggle');
        const label = document.getElementById('gps-label');
        const icon = document.getElementById('gps-icon');
        if (!btn) return;

        if (on) {
            btn.className = 'bg-green-500 hover:bg-green-400 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-lg transition flex items-center gap-1.5';
            if (label) label.textContent = '📡 GPS ON';
        } else {
            btn.className = 'bg-red-500/80 hover:bg-red-400 text-white px-3 py-2 rounded-xl text-xs font-bold shadow-lg transition flex items-center gap-1.5';
            if (label) label.textContent = '📡 GPS OFF';
            if (icon) icon.innerHTML = '<span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white/50"></span>';
        }
    }

    // ─── PUBLIC API ───
    window._tracker = { toggleTracking: () => tracking ? stopTracking() : startTracking() };

    // Init
    document.addEventListener('DOMContentLoaded', initMap);
    document.addEventListener('livewire:navigated', initMap);
})();
</script>
@endpush
