<div class="max-w-4xl mx-auto px-4 py-6">

    @if(!$work)
        <p class="text-gray-400 text-center py-20">ไม่พบงานนี้</p>
    @else
    <div class="space-y-6">

        {{-- Image --}}
        <div class="rounded-2xl overflow-hidden aspect-video bg-gray-800">
            <img src="{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/800/450' }}"
                 class="w-full h-full object-cover" alt="{{ $work->title }}">
        </div>

        {{-- Title + actions --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 flex-wrap text-sm text-gray-400 mb-1">
                    <span class="bg-orange-500/20 text-orange-400 px-2 py-0.5 rounded-full text-xs">
                        {{ $work->workType?->name ?? '-' }}
                    </span>
                    <span>📍 {{ $work->province?->name_th ?? '-' }}</span>
                    @if($work->avg_review_rating > 0)
                        <span class="text-yellow-400">⭐ {{ number_format($work->avg_review_rating,1) }}</span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-white">{{ $work->title }}</h1>
                <p class="text-3xl font-black text-orange-400 mt-1">฿{{ number_format($work->price) }}</p>
            </div>
            <button wire:click="toggleLike"
                    class="flex items-center gap-1.5 px-4 py-2 rounded-full border transition
                           {{ $isLiked ? 'border-red-500 text-red-400 bg-red-500/10' : 'border-gray-700 text-gray-400 hover:border-red-500 hover:text-red-400' }}">
                ❤️ {{ $work->like_count }}
            </button>
        </div>

        {{-- Description --}}
        <div class="bg-gray-900 rounded-xl p-4">
            <h2 class="font-bold text-white mb-2">รายละเอียด</h2>
            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $work->description }}</p>
        </div>

        {{-- Provider --}}
        <div class="bg-gray-900 rounded-xl p-4 flex items-center gap-4">
            <img src="{{ $work->author?->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($work->author?->name ?? 'U') }}"
                 class="w-12 h-12 rounded-full object-cover ring-2 ring-orange-500/30" alt="">
            <div>
                <p class="font-semibold text-white">{{ $work->author?->name }}</p>
                <p class="text-gray-400 text-sm">ผู้ให้บริการ</p>
            </div>
        </div>

        {{-- Map Location --}}
        @if($work->latitude && $work->longitude)
            <div class="bg-gray-900 rounded-xl p-4">
                <h2 class="font-bold text-white mb-3">📍 ตำแหน่งงาน</h2>
                <div id="work-map" class="w-full h-64 md:h-80 rounded-xl overflow-hidden"></div>
            </div>
        @endif

        {{-- ═══ Owner Actions ═══ --}}
        @if($isOwner)
        <div class="bg-gradient-to-r from-orange-500/10 to-orange-600/5 border border-orange-500/30 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-orange-400 text-sm font-bold">👑 คุณเป็นเจ้าของงานนี้</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('frontend.works.edit', $work->id) }}"
                   class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl text-sm transition flex items-center gap-1.5">
                    ✏️ แก้ไขงาน
                </a>
                <a href="{{ route('frontend.works.bookings', $work->id) }}"
                   class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-xl text-sm transition flex items-center gap-1.5 ring-1 ring-gray-700">
                    📋 รายการจอง ({{ count($bookings) }})
                </a>
            </div>
        </div>

        {{-- ═══ Bookings List (Owner Only) ═══ --}}
        @if($showBookings)
        <div class="bg-gray-900 rounded-2xl p-4">
            <h2 class="font-bold text-white mb-4">📋 รายการคนจอง ({{ count($bookings) }})</h2>

            @if(count($bookings) === 0)
                <p class="text-gray-500 text-sm text-center py-6">ยังไม่มีการจอง</p>
            @else
                <div class="space-y-3">
                    @foreach($bookings as $booking)
                        <div class="bg-gray-800 rounded-xl p-4 {{ $booking['status'] === 'waiting-to-confirm' ? 'ring-1 ring-orange-500/30' : '' }}">
                            <div class="flex items-start gap-3">
                                <img src="{{ $booking['author_avatar'] }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-white font-semibold text-sm">{{ $booking['author_name'] }}</span>
                                        @php
                                            $statusConfig = match($booking['status']) {
                                                'waiting-to-confirm' => ['bg-yellow-500/15 text-yellow-400 ring-yellow-500/30', '⏳ รอยืนยัน'],
                                                'confirm'            => ['bg-green-500/15 text-green-400 ring-green-500/30', '✅ ยืนยันแล้ว'],
                                                'close'              => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', '🔒 ปิดแล้ว'],
                                                'cancel'             => ['bg-red-500/15 text-red-400 ring-red-500/30', '❌ ยกเลิก'],
                                                default              => ['bg-gray-500/15 text-gray-400 ring-gray-500/30', $booking['status']],
                                            };
                                        @endphp
                                        <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full ring-1 {{ $statusConfig[0] }}">
                                            {{ $statusConfig[1] }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-400 mt-1">
                                        <span>📅 {{ $booking['date'] ?? '-' }}</span>
                                        <span>📞 {{ $booking['phone'] ?? '-' }}</span>
                                    </div>

                                    @if($booking['message'])
                                        <p class="text-gray-300 text-xs mt-2 bg-gray-900/60 rounded-lg px-3 py-2">
                                            💬 {{ $booking['message'] }}
                                        </p>
                                    @endif

                                    <p class="text-gray-600 text-[10px] mt-1">{{ $booking['created_at'] }}</p>

                                    {{-- Actions for waiting bookings --}}
                                    @if($booking['status'] === 'waiting-to-confirm')
                                        <div class="flex gap-2 mt-3">
                                            <button wire:click="confirmBooking({{ $booking['id'] }})"
                                                    wire:confirm="ยืนยันการจองนี้?"
                                                    class="px-4 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded-lg transition">
                                                ✅ ยืนยัน
                                            </button>
                                            <button wire:click="cancelBooking({{ $booking['id'] }})"
                                                    wire:confirm="ต้องการยกเลิกการจองนี้?"
                                                    class="px-4 py-1.5 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-xs font-bold rounded-lg transition ring-1 ring-red-500/30">
                                                ❌ ยกเลิก
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif
        @endif

        {{-- Booking Form --}}
        <div class="bg-gray-900 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">📋 จองงานนี้</h2>
                @auth
                    <button wire:click="$toggle('showBookingForm')"
                            class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-full text-sm transition">
                        {{ $showBookingForm ? 'ยกเลิก' : 'จองเลย' }}
                    </button>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-full text-sm transition">
                        เข้าสู่ระบบเพื่อจอง
                    </a>
                @endauth
            </div>

            @if($bookingMessage2)
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm">
                    {{ $bookingMessage2 }}
                </div>
            @endif

            @if($showBookingForm)
                <form wire:submit="submitBooking" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">เบอร์โทรติดต่อ *</label>
                            <input wire:model="bookingPhone" type="tel" placeholder="08x-xxx-xxxx"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
                            @error('bookingPhone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">วันที่ต้องการ *</label>
                            <input wire:model="bookingDate" type="date"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
                            @error('bookingDate')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs mb-1">ข้อความ (ถ้ามี)</label>
                        <textarea wire:model="bookingMessage" rows="3" placeholder="รายละเอียดเพิ่มเติม..."
                                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500 resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg transition">
                        ✅ ยืนยันการจอง
                    </button>
                </form>
            @endif
        </div>

        {{-- Reviews --}}
        <div class="bg-gray-900 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">⭐ รีวิว ({{ count($reviews ?? []) }})</h2>
                @auth
                    <button wire:click="$toggle('showReviewForm')"
                            class="px-3 py-1.5 border border-gray-700 rounded-full text-sm text-gray-400 hover:border-orange-500 hover:text-orange-400 transition">
                        {{ $showReviewForm ? 'ยกเลิก' : '+ เขียนรีวิว' }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-orange-400 hover:underline">เข้าสู่ระบบเพื่อรีวิว</a>
                @endauth
            </div>

            @if($reviewMessage)
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm mb-4">
                    {{ $reviewMessage }}
                </div>
            @endif

            @if($showReviewForm)
                <form wire:submit="submitReview" class="bg-gray-800 rounded-xl p-4 mb-4 space-y-3">
                    <input wire:model="reviewTitle" type="text" placeholder="หัวข้อรีวิว (ไม่บังคับ)"
                           class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 text-sm">คะแนน:</span>
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('reviewRating', {{ $i }})"
                                    class="text-2xl transition {{ $reviewRating >= $i ? 'text-yellow-400' : 'text-gray-600' }}">★</button>
                        @endfor
                    </div>
                    <textarea wire:model="reviewContent" rows="3" placeholder="แชร์ประสบการณ์ของคุณ..." required
                              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500 resize-none"></textarea>
                    @error('reviewContent')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                    <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg transition text-sm">
                        ส่งรีวิว
                    </button>
                </form>
            @endif

            @forelse($reviews ?? [] as $review)
                <div class="border-t border-gray-800 py-4">
                    <div class="flex items-start gap-3">
                        <img src="{{ $review['author']['profile_image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($review['author']['name'] ?? 'U') }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm text-white">{{ $review['author']['name'] ?? '-' }}</span>
                                @if(($review['rating'] ?? 0) > 0)
                                    <span class="text-yellow-400 text-xs">{{ str_repeat('★', $review['rating']) }}</span>
                                @endif
                            </div>
                            @if($review['title'] ?? null)
                                <p class="font-semibold text-gray-300 text-sm">{{ $review['title'] }}</p>
                            @endif
                            <p class="text-gray-300 text-sm">{{ $review['content'] }}</p>
                            <p class="text-gray-600 text-xs mt-1">{{ \Illuminate\Support\Carbon::parse($review['created_at'])->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-4">ยังไม่มีรีวิว</p>
            @endforelse
        </div>

    </div>
    @endif

</div>

@if($work && $work->latitude && $work->longitude)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = new maplibregl.Map({
        container: 'work-map',
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
        center: [{{ $work->longitude }}, {{ $work->latitude }}],
        zoom: 13
    });

    map.addControl(new maplibregl.NavigationControl(), 'top-right');

    // Custom orange marker
    const markerEl = document.createElement('div');
    markerEl.innerHTML = `
        <svg width="36" height="48" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 0C8.07 0 0 8.07 0 18c0 13.5 18 30 18 30s18-16.5 18-30C36 8.07 27.93 0 18 0z" fill="#F97316"/>
            <circle cx="18" cy="18" r="8" fill="white"/>
            <circle cx="18" cy="18" r="4" fill="#F97316"/>
        </svg>`;
    markerEl.style.cursor = 'pointer';

    const popup = new maplibregl.Popup({ offset: 25, closeButton: false })
        .setHTML(`
            <div style="padding:8px;font-family:sans-serif;">
                <p style="font-weight:700;font-size:14px;margin:0;">{{ addslashes($work->title) }}</p>
                <p style="color:#666;font-size:12px;margin:4px 0 0;">📍 {{ $work->province?->name_th ?? '-' }}</p>
                <p style="color:#F97316;font-weight:700;font-size:14px;margin:4px 0 0;">฿{{ number_format($work->price) }}</p>
            </div>
        `);

    new maplibregl.Marker({ element: markerEl })
        .setLngLat([{{ $work->longitude }}, {{ $work->latitude }}])
        .setPopup(popup)
        .addTo(map);
});
</script>
@endpush
@endif

