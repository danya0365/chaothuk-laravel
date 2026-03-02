<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">💼 Works Explorer</h1>
            <p class="mt-1 text-gray-400">ดู Works ทั้งหมดและทดสอบ API</p>
        </div>
        <button wire:click="loadWorks"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium border border-white/10 text-gray-300 hover:border-orange-500/40 hover:text-white transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </button>
    </div>

    @if($errorMessage)
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-sm text-rose-400">
            ⚠️ {{ $errorMessage }}
        </div>
    @endif

    @if($reviewSuccess)
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-400">
            {{ $reviewSuccess }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Works List --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
                <div class="p-4 border-b border-white/10">
                    <h3 class="text-sm font-semibold text-white">Works List</h3>
                    <p class="text-xs text-gray-500 mt-0.5">GET /api/works</p>
                </div>

                <div wire:loading.class="opacity-50" class="transition-opacity">
                    @if($works && isset($works['data']))
                        <div class="divide-y divide-white/5 max-h-[600px] overflow-y-auto">
                            @foreach($works['data'] as $work)
                                <button wire:click="selectWork({{ $work['id'] }})"
                                        class="w-full text-left p-4 hover:bg-white/5 transition-colors
                                               {{ (($selectedWork['data']['id'] ?? null) == $work['id']) ? 'bg-orange-500/10 border-l-2 border-orange-500' : '' }}">
                                    <p class="text-sm font-medium text-white truncate">{{ $work['title'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 truncate">
                                        ฿{{ number_format($work['price'] ?? 0) }}
                                        @if(!empty($work['avg_review_rating']))
                                            · ⭐ {{ $work['avg_review_rating'] }}
                                        @endif
                                    </p>
                                </button>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="p-3 border-t border-white/10 flex items-center justify-between text-xs text-gray-500">
                            <span>Page {{ $page }} of {{ $works['meta']['last_page'] ?? '?' }}</span>
                            <div class="flex gap-2">
                                @if($page > 1)
                                    <button wire:click="$set('page', {{ $page - 1 }}); loadWorks()" class="px-2 py-1 rounded bg-white/10 hover:bg-white/20 text-white">←</button>
                                @endif
                                @if(isset($works['meta']['last_page']) && $page < $works['meta']['last_page'])
                                    <button wire:click="$set('page', {{ $page + 1 }}); loadWorks()" class="px-2 py-1 rounded bg-white/10 hover:bg-white/20 text-white">→</button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center text-sm text-gray-500">
                            <span wire:loading>⏳ Loading...</span>
                            <span wire:loading.remove>No data</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Work Detail --}}
        <div class="lg:col-span-2">
            @if($selectedWork && isset($selectedWork['data']))
                @php $w = $selectedWork['data']; @endphp
                <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden mb-4">
                    {{-- Image --}}
                    @if(!empty($w['primary_image']))
                        <img src="{{ $w['primary_image'] }}" alt="{{ $w['title'] }}" class="w-full h-48 object-cover opacity-80">
                    @endif

                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-white">{{ $w['title'] }}</h2>
                                <p class="text-sm text-gray-400 mt-1">{{ $w['author']['full_name'] ?? 'N/A' }} · {{ $w['province']['name_th'] ?? '' }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-2xl font-bold text-orange-400">฿{{ number_format($w['price'] ?? 0) }}</p>
                                @if(!empty($w['avg_review_rating']))
                                    <p class="text-sm text-yellow-400">⭐ {{ $w['avg_review_rating'] }}</p>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-gray-300 leading-relaxed mb-5">{{ $w['description'] ?? '-' }}</p>

                        {{-- Raw JSON toggle --}}
                        <details class="text-xs">
                            <summary class="text-gray-500 cursor-pointer hover:text-gray-300 transition-colors mb-2">🔍 Raw API Response</summary>
                            <pre class="text-gray-400 bg-black/40 rounded-xl p-4 overflow-auto max-h-48 border border-white/5">{{ json_encode($selectedWork, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>

                        {{-- Review Button --}}
                        @if(session('api_token'))
                            <button wire:click="$toggle('showReviewForm')"
                                    class="mt-4 px-4 py-2 rounded-xl text-sm font-medium bg-orange-500/10 border border-orange-500/30 text-orange-400 hover:bg-orange-500/20 transition-all">
                                ⭐ {{ $showReviewForm ? 'ยกเลิก' : 'เขียน Review' }}
                            </button>
                        @endif
                    </div>

                    {{-- Review Form --}}
                    @if($showReviewForm)
                        <div class="border-t border-white/10 p-6">
                            <h3 class="text-sm font-semibold text-white mb-4">📝 POST /api/works/{id}/reviews</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Rating</label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button wire:click="$set('reviewRating', {{ $i }})"
                                                    class="w-9 h-9 rounded-lg text-lg transition-all
                                                           {{ $reviewRating >= $i ? 'text-yellow-400 bg-yellow-400/10' : 'text-gray-600 hover:text-yellow-400' }}">★</button>
                                        @endfor
                                        <span class="self-center text-sm text-gray-400 ml-2">{{ $reviewRating }}/5</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Title (optional)</label>
                                    <input wire:model="reviewTitle" type="text" placeholder="Review title..."
                                           class="w-full px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-600 focus:outline-none focus:border-orange-500/50 text-sm">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Content *</label>
                                    <textarea wire:model="reviewContent" rows="3" placeholder="เขียน review..."
                                              class="w-full px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-600 focus:outline-none focus:border-orange-500/50 text-sm resize-none"></textarea>
                                </div>
                                <button wire:click="submitReview"
                                        class="px-5 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-orange-500 to-rose-600 text-white hover:opacity-90 transition-all">
                                    ส่ง Review
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Reviews --}}
                @if($reviews && isset($reviews['data']))
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <h3 class="text-sm font-semibold text-white mb-4">💬 Reviews ({{ count($reviews['data']) }})</h3>
                        <div class="space-y-3">
                            @forelse($reviews['data'] as $review)
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-medium text-white">{{ $review['author']['full_name'] ?? 'Anonymous' }}</p>
                                        <span class="text-xs text-yellow-400">{{ str_repeat('⭐', $review['rating'] ?? 0) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-300">{{ $review['content'] ?? '' }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">ยังไม่มี review</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-dashed border-white/10 h-64 flex items-center justify-center">
                    <div class="text-center">
                        <span class="text-4xl block mb-3">👈</span>
                        <p class="text-gray-500 text-sm">เลือก Work จากรายการทางซ้ายเพื่อดูรายละเอียด</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
