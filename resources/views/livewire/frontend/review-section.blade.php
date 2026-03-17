<div>
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-3 md:mb-4">
            <div class="flex items-center gap-2 md:gap-3">
                <h2 class="font-bold text-white text-sm md:text-base">⭐ รีวิว</h2>
                <span class="text-gray-500 text-xs md:text-sm">({{ $reviews->count() }})</span>
            </div>
            @auth
                @if(auth()->user()->isPermission(\App\Enums\Permission::REVIEW_WORK->value))
                    <button wire:click="$toggle('showReviewForm')"
                            class="px-2.5 md:px-3 py-1 md:py-1.5 border border-gray-700 rounded-full text-[10px] md:text-xs text-gray-400 hover:border-orange-500 hover:text-orange-400 transition">
                        {{ $showReviewForm ? 'ยกเลิก' : '+ เขียนรีวิว' }}
                    </button>
                @else
                    <span class="text-[10px] md:text-xs text-gray-500 italic">ไม่มีสิทธิรีวิว</span>
                @endif
            @else
                <a href="{{ route('frontend.auth.login') }}" class="text-xs md:text-sm text-orange-400 hover:underline">เข้าสู่ระบบเพื่อรีวิว</a>
            @endauth
        </div>

        {{-- Success message --}}
        @if($reviewMessage)
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm mb-3 md:mb-4">
                {{ $reviewMessage }}
            </div>
        @endif

        {{-- New review form --}}
        @if($showReviewForm)
            <form wire:submit="submitReview" class="bg-gray-800 rounded-xl p-3 md:p-4 mb-3 md:mb-4 space-y-2 md:space-y-3">
                <input wire:model="reviewTitle" type="text" placeholder="หัวข้อรีวิว (ไม่บังคับ)"
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-2.5 py-1.5 md:px-3 md:py-2 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-xs md:text-sm">คะแนน:</span>
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" wire:click="$set('reviewRating', {{ $i }})"
                                class="text-xl md:text-2xl transition {{ $reviewRating >= $i ? 'text-yellow-400' : 'text-gray-600' }}">★</button>
                    @endfor
                </div>
                <textarea wire:model="reviewContent" rows="3" placeholder="แชร์ประสบการณ์ของคุณ..." required
                          class="w-full bg-gray-700 border border-gray-600 rounded-lg px-2.5 py-1.5 md:px-3 md:py-2 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500 resize-none"></textarea>
                @error('reviewContent')<p class="text-red-400 text-[10px] md:text-xs">{{ $message }}</p>@enderror
                <button type="submit" class="px-4 py-1.5 md:px-6 md:py-2 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-lg transition text-xs md:text-sm">
                    ส่งรีวิว
                </button>
            </form>
        @endif

        {{-- Reviews list with nested replies --}}
        @forelse($reviews as $review)
            @include('livewire.frontend._review-item', ['post' => $review, 'depth' => 0])
        @empty
            <p class="text-gray-500 text-xs md:text-sm text-center py-3 md:py-4">ยังไม่มีรีวิว ให้คะแนนเป็นคนแรก!</p>
        @endforelse

        <div class="mt-3 md:mt-4 text-xs">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
