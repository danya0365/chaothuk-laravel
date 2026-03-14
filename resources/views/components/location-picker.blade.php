@props(['label' => 'เลือกตำแหน่งบนแผนที่'])

<div x-data="locationPickerData()"
     x-init="initComponent()"
     @keydown.escape.window="closeModal()">

    {{-- Trigger Button --}}
    <button @click="openModal()" type="button"
            {{ $attributes->merge(['class' => 'px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-xl text-gray-300 font-semibold transition flex items-center justify-center gap-2']) }}>
        <span class="text-sm">{{ $label }}</span>
    </button>

    {{-- Modal Popup --}}
    <div x-show="showModal"
         x-transition.opacity
         style="display: none;"
         class="fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4">

        <div @click.away="closeModal()"
             class="bg-gray-900 rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl border border-gray-800 flex flex-col max-h-[90vh]">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    📍 ระบุตำแหน่งค้นหา
                </h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-white transition">✕</button>
            </div>

            {{-- Map Container --}}
            <div wire:ignore class="relative w-full flex-grow" style="height: 50vh; min-height: 300px;">
                <div id="map-{{ $attributes->wire('model.lat')->value() ?? 'picker' }}" class="w-full h-full"></div>

                {{-- Find Me Button overlay --}}
                <button type="button" @click="detectLocation()"
                        class="absolute bottom-4 right-4 bg-white text-gray-900 px-4 py-2 rounded-xl font-bold shadow-lg hover:bg-gray-100 flex items-center gap-2 transition"
                        title="ดึงตำแหน่งปัจจุบัน">
                    <span x-show="!loadingLoc">🎯 ตำแหน่งฉัน</span>
                    <span x-show="loadingLoc" class="animate-spin" style="display: none;">⏳</span>
                </button>
            </div>

            {{-- Footer / Coordinates Feedback --}}
            <div class="p-4 border-t border-gray-800 bg-gray-900 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-gray-400 text-xs text-center sm:text-left">
                    <p>เลื่อนหมุดหรือคลิกบนแผนที่เพื่อเลือกจุดศูนย์กลาง</p>
                    <template x-if="pickerLat && pickerLng">
                        <p class="text-orange-400 font-mono mt-0.5">
                            <span x-text="pickerLat.toFixed(5)"></span>, <span x-text="pickerLng.toFixed(5)"></span>
                        </p>
                    </template>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="button" @click="closeModal()" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-gray-800 hover:bg-gray-700 text-white font-bold transition">
                        ยกเลิก
                    </button>
                    <button type="button" @click="confirmLocation()" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-400 text-white font-bold transition">
                        ✅ ยืนยันตำแหน่ง
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('locationPickerData', () => ({
            showModal: false,
            pickerLat: null,
            pickerLng: null,
            loadingLoc: false,
            map: null,
            marker: null,
            mapId: 'map-{{ $attributes->wire("model.lat")->value() ?? "picker" }}',
            wireLatProp: '{{ $attributes->wire("model.lat")->value() }}',
            wireLngProp: '{{ $attributes->wire("model.lng")->value() }}',

            initComponent() {
                // Determine initial values from Livewire
                let wireLat = this.wireLatProp ? parseFloat(@this.get(this.wireLatProp)) : null;
                let wireLng = this.wireLngProp ? parseFloat(@this.get(this.wireLngProp)) : null;

                if (wireLat && wireLng) {
                    this.pickerLat = wireLat;
                    this.pickerLng = wireLng;
                }
            },

            initMap() {
                if (this.map) {
                    // Map already exists, just resize and center
                    this.map.resize();
                    if (this.pickerLat && this.pickerLng) {
                        this.map.setCenter([this.pickerLng, this.pickerLat]);
                        this.marker.setLngLat([this.pickerLng, this.pickerLat]);
                    }
                    return;
                }

                // Check if we need to auto-detect location
                let needsDetection = (!this.pickerLat || !this.pickerLng);

                // Default to Bangkok
                let startLat = this.pickerLat || 13.7563;
                let startLng = this.pickerLng || 100.5018;

                this.map = new maplibregl.Map({
                    container: this.mapId,
                    style: {
                        version: 8,
                        sources: {
                            'osm-tiles': {
                                type: 'raster',
                                tiles: ['https://tile.openstreetmap.org/{z}/{x}/{y}.png'],
                                tileSize: 256,
                                attribution: '&copy; OpenStreetMap'
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
                    center: [startLng, startLat],
                    zoom: 11
                });

                this.map.addControl(new maplibregl.NavigationControl(), 'top-right');

                this.marker = new maplibregl.Marker({ color: '#f97316', draggable: true })
                    .setLngLat([startLng, startLat])
                    .addTo(this.map);

                this.marker.on('dragend', () => {
                    const lngLat = this.marker.getLngLat();
                    this.pickerLat = lngLat.lat;
                    this.pickerLng = lngLat.lng;
                });

                this.map.on('click', (e) => {
                    const { lat, lng } = e.lngLat;
                    this.pickerLat = lat;
                    this.pickerLng = lng;
                    this.marker.setLngLat([lng, lat]);
                });

                // Auto-detect if no initial location was provided
                if (needsDetection) {
                    this.detectLocation();
                }
            },

            openModal() {
                this.showModal = true;
                // Important: wait for the DOM to update so the div is visible before initializing map
                setTimeout(() => {
                    this.initMap();
                }, 10);
            },

            closeModal() {
                this.showModal = false;
            },

            detectLocation() {
                this.loadingLoc = true;
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        this.pickerLat = pos.coords.latitude;
                        this.pickerLng = pos.coords.longitude;
                        if (this.map && this.marker) {
                            this.map.flyTo({ center: [this.pickerLng, this.pickerLat], zoom: 14 });
                            this.marker.setLngLat([this.pickerLng, this.pickerLat]);
                        }
                        this.loadingLoc = false;
                    },
                    (error) => {
                        console.error("Geolocation error:", error);
                        // Silently fail to Bangkok default if auto-detecting, otherwise alert wasn't necessary anyway since it falls back gracefully
                        this.loadingLoc = false;
                    },
                    { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                );
            },

            confirmLocation() {
                if (!this.pickerLat || !this.pickerLng) {
                    alert('กรุณาเลือกตำแหน่งบนแผนที่');
                    return;
                }

                if (this.wireLatProp && this.wireLngProp) {
                    @this.set(this.wireLatProp, this.pickerLat);
                    @this.set(this.wireLngProp, this.pickerLng);
                }

                window.dispatchEvent(new CustomEvent('location-picked', {
                    detail: { lat: this.pickerLat, lng: this.pickerLng }
                }));
                this.closeModal();
            }
        }));
    });
</script>
@endpush
