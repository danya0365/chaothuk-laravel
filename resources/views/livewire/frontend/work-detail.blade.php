<div class="max-w-4xl mx-auto px-4 py-6">

    @if(!$work)
        <p class="text-gray-400 text-center py-20">ไม่พบงานนี้</p>
    @else
    <div class="space-y-4">

        {{-- ═══════════════════════════════════════════════════════════════════
             1. HERO IMAGE + GALLERY
        ═══════════════════════════════════════════════════════════════════ --}}
        @php
            $galleryImages = is_array($work->images) ? $work->images : (is_string($work->images) ? json_decode($work->images, true) ?? [] : []);
        @endphp
        <div x-data="{ activeImage: '{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/800/450' }}', allImages: @js(array_merge([$work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/800/450'], $galleryImages)) }"
             class="space-y-2">
            <div class="rounded-2xl overflow-hidden aspect-video bg-gray-800 relative">
                <img :src="activeImage" class="w-full h-full object-cover" alt="{{ $work->title }}">
                {{-- Status badge --}}
                @php
                    $statusBadge = match($work->work_status) {
                        'stand-by' => ['🟢', 'พร้อมรับงาน', 'bg-green-500/90'],
                        'busy'     => ['🟡', 'ไม่ว่าง', 'bg-yellow-500/90'],
                        'close'    => ['🔴', 'ปิดรับงาน', 'bg-red-500/90'],
                        default    => ['⚪', $work->work_status, 'bg-gray-500/90'],
                    };
                @endphp
                <span class="absolute top-3 left-3 {{ $statusBadge[2] }} text-white text-xs font-bold px-3 py-1.5 rounded-lg backdrop-blur">
                    {{ $statusBadge[0] }} {{ $statusBadge[1] }}
                </span>
            </div>
            {{-- Thumbnail gallery --}}
            @if(count($galleryImages) > 0)
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button @click="activeImage = '{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/800/450' }}'"
                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden ring-2 transition"
                        :class="activeImage === '{{ $work->primary_image ?? '' }}' ? 'ring-orange-500' : 'ring-transparent hover:ring-gray-600'">
                    <img src="{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/800/450' }}" class="w-full h-full object-cover" alt="">
                </button>
                @foreach($galleryImages as $img)
                    <button @click="activeImage = '{{ $img }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden ring-2 transition"
                            :class="activeImage === '{{ $img }}' ? 'ring-orange-500' : 'ring-transparent hover:ring-gray-600'">
                        <img src="{{ $img }}" class="w-full h-full object-cover" alt="">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             2. TITLE + PRICE + QUICK STATS BAR
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-5">
            {{-- Tags row --}}
            <div class="flex items-center gap-2 flex-wrap mb-2">
                <span class="bg-orange-500/20 text-orange-400 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                    {{ $work->workType?->name ?? '-' }}
                </span>
                @foreach($work->categories as $cat)
                    <span class="bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full text-[11px]">{{ $cat->name }}</span>
                @endforeach
                <span class="text-gray-600 text-[11px]">📍 {{ $work->province?->name_th ?? '-' }}</span>
                <span class="text-gray-700 text-[11px]">#{{ $work->code ?? 'W'.$work->id }}</span>
            </div>

            {{-- Title + Like --}}
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white leading-tight">{{ $work->title }}</h1>
                    <p class="text-3xl font-black text-orange-400 mt-1">฿{{ number_format($work->price) }}</p>
                </div>
                <button wire:click="toggleLike"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-full border transition flex-shrink-0
                               {{ $isLiked ? 'border-red-500 text-red-400 bg-red-500/10' : 'border-gray-700 text-gray-400 hover:border-red-500 hover:text-red-400' }}">
                    ❤️ {{ $work->like_count }}
                </button>
            </div>

            {{-- Stats strip --}}
            <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-800 text-sm">
                @if($work->avg_review_rating > 0)
                    <span class="text-yellow-400 font-bold flex items-center gap-1">
                        ⭐ {{ number_format($work->avg_review_rating, 1) }}
                        <span class="text-gray-500 font-normal">({{ $work->reply_count ?? count($reviews ?? []) }})</span>
                    </span>
                @endif
                <span class="text-gray-500 flex items-center gap-1">❤️ {{ $work->like_count }} ถูกใจ</span>
                <span class="text-gray-500 flex items-center gap-1">📋 {{ count($bookedDates) }} จอง</span>
                <span class="text-gray-600 text-xs">เผยแพร่ {{ $work->created_at?->diffForHumans() }}</span>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             3. OWNER BAR (if owner)
        ═══════════════════════════════════════════════════════════════════ --}}
        @if($isOwner)
        <div class="bg-gradient-to-r from-orange-500/10 to-orange-600/5 border border-orange-500/30 rounded-2xl p-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <span class="text-orange-400 text-sm font-bold">👑 คุณเป็นเจ้าของงานนี้</span>
                <div class="flex gap-2">
                    <a href="{{ route('frontend.works.edit', $work->id) }}"
                       class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl text-sm transition">
                        ✏️ แก้ไข
                    </a>
                    <a href="{{ route('frontend.works.bookings', $work->id) }}"
                       class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-xl text-sm transition ring-1 ring-gray-700">
                        📋 จัดการจอง ({{ count($bookings) }})
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════════════════
             4. PROVIDER CARD (with reputation)
        ═══════════════════════════════════════════════════════════════════ --}}
        <a href="{{ route('frontend.reputation', $work->author_id) }}"
           class="bg-gray-900 rounded-2xl p-4 flex items-center gap-4 hover:ring-2 hover:ring-orange-500/40 transition block group">
            <img src="{{ $work->author?->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($work->author?->name ?? 'U') }}"
                 class="w-14 h-14 rounded-xl object-cover ring-2 ring-orange-500/30" alt="">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="font-bold text-white group-hover:text-orange-400 transition">{{ $work->author?->name }}</p>
                    @if($providerStats && $providerStats['trust_level'])
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                            {{ match($providerStats['trust_level']) {
                                'diamond'  => 'bg-purple-500/20 text-purple-300',
                                'platinum' => 'bg-blue-500/20 text-blue-300',
                                'gold'     => 'bg-yellow-500/20 text-yellow-300',
                                'silver'   => 'bg-gray-400/20 text-gray-300',
                                'bronze'   => 'bg-orange-700/20 text-orange-400',
                                default    => 'bg-gray-700/20 text-gray-400',
                            } }}">
                            {{ $providerStats['trust_label'] }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400 mt-0.5">
                    <span>📦 {{ $providerStats['total_works'] ?? 0 }} งาน</span>
                    <span>✅ {{ $providerStats['completed_jobs'] ?? 0 }} สำเร็จ</span>
                    @if(($providerStats['completion_rate'] ?? 0) > 0)
                        <span class="{{ $providerStats['completion_rate'] >= 80 ? 'text-green-400' : 'text-yellow-400' }}">
                            📊 {{ $providerStats['completion_rate'] }}%
                        </span>
                    @endif
                    @if($providerStats['overall_score'])
                        <span class="text-yellow-400">⭐ {{ $providerStats['overall_score'] }}</span>
                    @endif
                </div>
            </div>
            <span class="text-gray-500 text-sm group-hover:text-orange-400 transition flex-shrink-0">ดูโปรไฟล์ →</span>
        </a>

        {{-- ═══════════════════════════════════════════════════════════════════
             5. DESCRIPTION + DETAILS
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-5">
            <h2 class="font-bold text-white mb-3 flex items-center gap-2">📝 รายละเอียด</h2>
            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $work->description }}</p>

            {{-- Details JSON table --}}
            @if(is_array($work->details) && count($work->details) > 0)
                <div class="mt-4 pt-4 border-t border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-400 mb-2">📋 ข้อมูลเพิ่มเติม</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($work->details as $key => $value)
                            <div class="bg-gray-800/50 rounded-lg px-3 py-2">
                                <span class="text-gray-500 text-[11px]">{{ $key }}</span>
                                <p class="text-white text-sm font-medium">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             6. BOOKING CTA + CALENDAR (most actionable section)
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-5"
             x-data="{
                 bookedDates: @js($bookedDates),
                 currentMonth: new Date().getMonth(),
                 currentYear: new Date().getFullYear(),
                 today: new Date().toISOString().slice(0,10),
                 selectedDate: null,
                 get monthName() {
                     const months = ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
                     return months[this.currentMonth] + ' ' + (this.currentYear + 543);
                 },
                 get daysInMonth() { return new Date(this.currentYear, this.currentMonth + 1, 0).getDate(); },
                 get firstDayOfWeek() { return new Date(this.currentYear, this.currentMonth, 1).getDay(); },
                 dateStr(day) {
                     return this.currentYear + '-' + String(this.currentMonth + 1).padStart(2,'0') + '-' + String(day).padStart(2,'0');
                 },
                 isBooked(day) { return this.bookedDates.includes(this.dateStr(day)); },
                 isToday(day) { return this.dateStr(day) === this.today; },
                 isPast(day) { return this.dateStr(day) < this.today; },
                 isAvailable(day) { return !this.isBooked(day) && !this.isPast(day); },
                 selectDate(day) {
                     if (!this.isAvailable(day)) return;
                     const ds = this.dateStr(day);
                     this.selectedDate = ds;
                     $wire.set('bookingDate', ds);
                     $wire.set('showBookingForm', true);
                     setTimeout(() => {
                         document.getElementById('booking-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                     }, 150);
                 },
                 prevMonth() { if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; } else { this.currentMonth--; } },
                 nextMonth() { if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; } else { this.currentMonth++; } }
             }">

            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">📅 เลือกวันจอง</h2>
                <div class="flex items-center gap-2">
                    <button @click="prevMonth()" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm flex items-center justify-center transition">‹</button>
                    <span class="text-white font-semibold text-sm min-w-[140px] text-center" x-text="monthName"></span>
                    <button @click="nextMonth()" class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm flex items-center justify-center transition">›</button>
                </div>
            </div>

            {{-- Day headers --}}
            <div class="grid grid-cols-7 gap-1 mb-1">
                <template x-for="d in ['อา','จ','อ','พ','พฤ','ศ','ส']" :key="d">
                    <div class="text-center text-gray-500 text-xs font-semibold py-1" x-text="d"></div>
                </template>
            </div>

            {{-- Day cells --}}
            <div class="grid grid-cols-7 gap-1">
                <template x-for="i in firstDayOfWeek" :key="'e'+i">
                    <div class="aspect-square"></div>
                </template>
                <template x-for="day in daysInMonth" :key="day">
                    <div @click="selectDate(day)"
                         class="aspect-square rounded-lg text-xs font-semibold flex items-center justify-center transition"
                         :class="{
                             'bg-red-500/20 text-red-400 ring-1 ring-red-500/30 cursor-not-allowed': isBooked(day),
                             'bg-green-500/10 text-green-400 hover:bg-green-500/30 hover:ring-2 hover:ring-green-400 cursor-pointer': isAvailable(day) && !isToday(day) && selectedDate !== dateStr(day),
                             'bg-orange-500 text-white ring-2 ring-orange-400 font-black hover:bg-orange-400 cursor-pointer': isToday(day) && !isBooked(day),
                             'bg-gray-800/50 text-gray-600 cursor-not-allowed': isPast(day) && !isBooked(day) && !isToday(day),
                             'bg-blue-500 text-white ring-2 ring-blue-400 font-black': selectedDate === dateStr(day),
                         }"
                         x-text="day">
                    </div>
                </template>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-800 flex-wrap text-[11px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-green-500/30 inline-block"></span> <span class="text-gray-500">ว่าง</span></span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-red-500/30 inline-block"></span> <span class="text-gray-500">ถูกจอง</span></span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-blue-500 inline-block"></span> <span class="text-gray-500">เลือกอยู่</span></span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-orange-500 inline-block"></span> <span class="text-gray-500">วันนี้</span></span>
            </div>

            {{-- Inline Booking Form --}}
            <div id="booking-form" class="mt-4 pt-4 border-t border-gray-800">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-white text-sm">📋 จองงานนี้</h3>
                    @auth
                        <button wire:click="$toggle('showBookingForm')"
                                class="px-4 py-1.5 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-lg text-xs transition">
                            {{ $showBookingForm ? 'ยกเลิก' : 'จองเลย' }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-1.5 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-lg text-xs transition">
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
                                <label class="block text-gray-400 text-xs mb-1">เบอร์โทร *</label>
                                <input wire:model="bookingPhone" type="tel" placeholder="08x-xxx-xxxx"
                                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
                                @error('bookingPhone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-400 text-xs mb-1">วันที่ *</label>
                                <input wire:model="bookingDate" type="date"
                                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
                                @error('bookingDate')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">ข้อความเพิ่มเติม</label>
                            <textarea wire:model="bookingMessage" rows="2" placeholder="บอกรายละเอียดที่ต้องการ..."
                                      class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500 resize-none"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg transition">
                            ✅ ยืนยันการจอง
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════
             7. MAP
        ═══════════════════════════════════════════════════════════════════ --}}
        @if($work->latitude && $work->longitude)
        <div class="bg-gray-900 rounded-2xl p-5">
            <h2 class="font-bold text-white mb-3">📍 ตำแหน่งงาน</h2>
            <div id="work-map" class="w-full h-64 md:h-72 rounded-xl overflow-hidden"></div>
            <p class="text-gray-600 text-[11px] mt-2">{{ $work->latitude }}, {{ $work->longitude }}</p>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════════════════
             8. REVIEWS
        ═══════════════════════════════════════════════════════════════════ --}}
        <div class="bg-gray-900 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-white">⭐ รีวิว</h2>
                    @if($work->avg_review_rating > 0)
                        <span class="text-yellow-400 font-bold text-sm">{{ number_format($work->avg_review_rating, 1) }}</span>
                        <span class="text-gray-500 text-sm">({{ count($reviews ?? []) }})</span>
                    @else
                        <span class="text-gray-500 text-sm">({{ count($reviews ?? []) }})</span>
                    @endif
                </div>
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
                                    <span class="text-yellow-400 text-xs">{{ str_repeat('★', $review['rating']) }}{{ str_repeat('☆', 5 - $review['rating']) }}</span>
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
                <p class="text-gray-500 text-sm text-center py-4">ยังไม่มีรีวิว ให้คะแนนเป็นคนแรก!</p>
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
