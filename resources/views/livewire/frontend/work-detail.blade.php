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
