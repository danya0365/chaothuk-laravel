<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input wire:model.live.debounce.400ms="search" type="text"
               placeholder="🔍 ค้นหางาน..."
               class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500 transition">

        <select wire:model.live="provinceId"
                class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-gray-300 focus:outline-none focus:border-orange-500 transition">
            <option value="">📍 ทุกจังหวัด</option>
            @foreach($provinces as $p)
                <option value="{{ $p->id }}">{{ $p->name_th }}</option>
            @endforeach
        </select>

        <select wire:model.live="workTypeId"
                class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-gray-300 focus:outline-none focus:border-orange-500 transition">
            <option value="">🚛 ประเภทงานทั้งหมด</option>
            @foreach($workTypes as $wt)
                <option value="{{ $wt->id }}">{{ $wt->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="sortBy"
                class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-gray-300 focus:outline-none focus:border-orange-500 transition">
            <option value="latest">ล่าสุด</option>
            <option value="popular">ยอดนิยม</option>
            <option value="rating">คะแนนสูงสุด</option>
        </select>
    </div>

    {{-- Loading --}}
    <div wire:loading class="text-center py-8 text-gray-400">กำลังโหลด...</div>

    {{-- Grid --}}
    <div wire:loading.remove>
        @if($works->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📭</div>
                <p class="text-gray-400">ไม่พบผลลัพธ์</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                @foreach($works as $work)
                    <div class="group bg-gray-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-orange-500/40 transition">
                        <a href="{{ route('frontend.works.show', $work->id) }}">
                            <div class="aspect-video bg-gray-800 overflow-hidden">
                                <img src="{{ $work->primary_image ?? 'https://picsum.photos/seed/'.$work->id.'/400/300' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $work->title }}">
                            </div>
                        </a>
                        <div class="p-3">
                            <a href="{{ route('frontend.works.show', $work->id) }}"
                               class="font-semibold text-sm text-white hover:text-orange-400 transition line-clamp-2 block mb-1">
                                {{ $work->title }}
                            </a>
                            <p class="text-orange-400 font-bold">฿{{ number_format($work->price) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-gray-500 text-xs">📍 {{ $work->province?->name_th ?? '-' }}</p>
                                <div class="flex items-center gap-2">
                                    @if($work->avg_review_rating > 0)
                                        <span class="text-yellow-400 text-xs">⭐ {{ number_format($work->avg_review_rating,1) }}</span>
                                    @endif
                                    <button wire:click="toggleLike({{ $work->id }})"
                                            class="text-xs transition {{ in_array($work->id, $likedIds) ? 'text-red-400' : 'text-gray-600 hover:text-red-400' }}">
                                        ❤️ {{ $work->like_count }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $works->links() }}
        @endif
    </div>

</div>
