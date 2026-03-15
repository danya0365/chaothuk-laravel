<div class="p-6 sm:p-10 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 text-sm">
        <div class="flex items-center space-x-4">
            <a href="{{ route('backend.logs.work-sessions.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                กลับไปหน้ารายการ
            </a>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">รายละเอียดเซสชันงาน #WS-{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Session Summary Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ข้อมูลเซสชัน
                    </h2>
                    @php
                        $statusClass = match($session->status) {
                            'active', 'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border-blue-200',
                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border-green-200',
                            'cancelled', 'aborted' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border-red-200',
                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 border-yellow-200',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border-gray-200',
                        };
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full border {{ $statusClass }}">
                        {{ __('common.work_session_status-' . $session->status) ?? $session->status }}
                    </span>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">อ้างอิงถึง</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($session->sessionable)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 mb-1">
                                        {{ class_basename($session->sessionable_type) }} #{{ $session->sessionable_id }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">ไม่พบข้อมูลอ้างอิงเซสชัน</span>
                                @endif
                                
                                @if($session->bookingable)
                                    <br>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                                        Booking: {{ class_basename($session->bookingable_type) }} #{{ $session->bookingable_id }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ราคาที่ตกลง</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">฿{{ number_format($session->price_agreed, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เวลาเริ่มต้น</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $session->started_at ? $session->started_at->format('d/m/Y H:i:s') : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">เวลาสิ้นสุด</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $session->ended_at ? $session->ended_at->format('d/m/Y H:i:s') : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ระยะเวลาสุทธิ</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $session->total_duration_minutes ? $session->total_duration_minutes . ' นาที' : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">รายละเอียดเพิ่มเติม/หมายเหตุ</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $session->notes ?: '-' }}</dd>
                        </div>
                        @if($session->status === 'cancelled' || $session->status === 'aborted')
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-red-500 dark:text-red-400">เหตุผลที่ยกเลิก</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $session->cancel_reason ?: 'ไม่ได้ระบุเหตุผล' }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Map Card (NEW) -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden relative" wire:ignore>
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        แผนที่การเดินทาง
                    </h2>
                </div>
                <!-- Map Container -->
                <div id="session-map" class="w-full" style="height: 480px;"></div>
                
                <!-- Legend (bottom-right) -->
                <div class="absolute bottom-4 right-4 z-10 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md rounded-xl px-3 py-2 shadow-lg border border-gray-200 dark:border-gray-700 flex items-center gap-3 text-[10px] sm:text-xs">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span><span class="text-gray-700 dark:text-gray-300">เริ่ม</span></span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span><span class="text-gray-700 dark:text-gray-300">เส้นทาง</span></span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 ring-2 ring-blue-400/50"></span><span class="text-gray-700 dark:text-gray-300">ปัจจุบัน/ล่าสุด</span></span>
                </div>
            </div>

            <!-- Locations Log Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        ประวัติตำแหน่ง (Location Logs)
                    </h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $session->locationLogs->count() }} รายการ</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">เวลาที่บันทึก</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">พิกัด (Lat, Lng)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">เหตุการณ์</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($session->locationLogs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        <a href="https://maps.google.com/?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                            {{ $log->latitude }}, {{ $log->longitude }}
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->event ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        ไม่มีการบันทึกตำแหน่งในเซสชันนี้
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6 lg:border-l lg:border-gray-200 lg:dark:border-gray-700 lg:pl-6">
            <!-- Worker (Provider) -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">ผู้ให้บริการ (Worker)</h3>
                @if($session->worker)
                    <div class="flex items-center space-x-4 mb-3">
                        <x-backend.avatar :src="$session->worker->getAvatar()" :name="$session->worker->name" size="h-12 w-12" />
                        <div>
                            <a href="{{ route('backend.users.show', $session->worker->id) }}" class="text-base font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                {{ $session->worker->name }}
                            </a>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $session->worker->email }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p>ID: #{{ $session->worker->id }}</p>
                        <p>ยืนยัน: {!! $session->worker_confirm ? '<span class="text-green-600 dark:text-green-400 font-medium">ยืนยันแล้ว</span>' : '<span class="text-yellow-600 dark:text-yellow-400 font-medium">รอยืนยัน</span>'  !!}</p>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Deleted User</div>
                @endif
            </div>

            <!-- Customer -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">ลูกค้า (Customer)</h3>
                @if($session->customer)
                    <div class="flex items-center space-x-4 mb-3">
                        <x-backend.avatar :src="$session->customer->getAvatar()" :name="$session->customer->name" size="h-12 w-12" />
                        <div>
                            <a href="{{ route('backend.users.show', $session->customer->id) }}" class="text-base font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                {{ $session->customer->name }}
                            </a>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $session->customer->email }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <p>ID: #{{ $session->customer->id }}</p>
                        <p>ยืนยัน: {!! $session->customer_confirm ? '<span class="text-green-600 dark:text-green-400 font-medium">ยืนยันแล้ว</span>' : '<span class="text-yellow-600 dark:text-yellow-400 font-medium">รอยืนยัน</span>'  !!}</p>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">Deleted User</div>
                @endif
            </div>
        </div>
    </div>
</div>

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
@keyframes markerPulse {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(2.5); opacity: 0; }
}
</style>
<script>
(function() {
    // ─── CONFIG ───
    @php
        $logsData = $session->locationLogs->map(function($log) {
            return [
                'lat' => $log->latitude,
                'lng' => $log->longitude,
                'accuracy' => $log->accuracy,
                'speed' => $log->speed,
                'recorded_at' => $log->created_at->format('d/m/Y H:i:s')
            ];
        })->values()->all();
    @endphp
    const INITIAL_LOGS = @json($logsData);

    // ─── STATE ───
    let map, routeSource, currentMarker, startMarker;
    let allPoints = INITIAL_LOGS.map(l => ({ lat: parseFloat(l.lat), lng: parseFloat(l.lng), accuracy: l.accuracy, speed: l.speed, heading: null, time: l.recorded_at }));

    // ─── INIT MAP ───
    function initMap() {
        if (map || !document.getElementById('session-map') || typeof window.maplibregl === 'undefined') return;

        const center = allPoints.length > 0
            ? [allPoints[allPoints.length-1].lng, allPoints[allPoints.length-1].lat]
            : [100.5018, 13.7563]; // Bangkok default
            
        const isDark = document.documentElement.classList.contains('dark');
        const mapStyle = isDark 
            ? 'https://basemaps.cartocdn.com/dark_all/{z}/{x}/{y}@2x.png' 
            : 'https://basemaps.cartocdn.com/light_all/{z}/{x}/{y}@2x.png';

        map = new window.maplibregl.Map({
            container: 'session-map',
            style: {
                version: 8,
                sources: {
                    'carto': {
                        type: 'raster',
                        tiles: [mapStyle],
                        tileSize: 256,
                        attribution: '&copy; <a href="https://carto.com">CARTO</a> &copy; <a href="https://www.openstreetmap.org">OSM</a>'
                    }
                },
                layers: [{ id: 'carto-layer', type: 'raster', source: 'carto', minzoom: 0, maxzoom: 20 }]
            },
            center: center,
            zoom: allPoints.length > 0 ? 16 : 6
        });

        map.addControl(new window.maplibregl.NavigationControl(), 'top-right');

        map.on('load', () => {
            if (allPoints.length === 0) return;

            // Route source
            map.addSource('route', {
                type: 'geojson',
                data: makeRouteLine()
            });

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
            placeStartMarker(allPoints[0]);
            placeCurrentMarker(allPoints[allPoints.length - 1]);
            fitToRoute();
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
        startMarker = new window.maplibregl.Marker({ element: el })
            .setLngLat([p.lng, p.lat])
            .setPopup(new window.maplibregl.Popup({ offset: 12, closeButton: false }).setHTML(
                '<div style="padding:6px;font-size:12px;font-family:sans-serif;color:#333;"><b>🟢 จุดเริ่มต้น</b><br><span style="color:#999;">' + (p.time || '') + '</span></div>'
            ))
            .addTo(map);
    }

    function placeCurrentMarker(p) {
        if (currentMarker) currentMarker.remove();

        const container = document.createElement('div');
        container.className = 'marker-current';
        const arrow = document.createElement('div');
        arrow.className = 'arrow';
        if (p.heading) arrow.style.transform = 'translateX(-50%) rotate(' + p.heading + 'deg)';
        container.appendChild(arrow);

        currentMarker = new window.maplibregl.Marker({ element: container })
            .setLngLat([p.lng, p.lat])
            .setPopup(new window.maplibregl.Popup({ offset: 16, closeButton: false }).setHTML(
                '<div style="padding:6px;font-size:12px;font-family:sans-serif;color:#333;"><b>📍 ล่าสุด/ปัจจุบัน</b><br>' +
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
        const bounds = new window.maplibregl.LngLatBounds();
        allPoints.forEach(p => bounds.extend([p.lng, p.lat]));
        map.fitBounds(bounds, { padding: 60, maxZoom: 17, duration: 1000 });
    }

    // Try to init, wait a bit in case window.maplibregl is not yet loaded
    setTimeout(initMap, 100);
    document.addEventListener('DOMContentLoaded', initMap);
    document.addEventListener('livewire:navigated', initMap);
})();
</script>
