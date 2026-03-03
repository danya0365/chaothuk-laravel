<div class="max-w-4xl mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold text-white mb-6">⭐ รายการโปรด</h1>

    {{-- Tabs --}}
    <div class="flex gap-2 mb-6">
        @foreach(['work' => '📦 งาน', 'recruit' => '👷 รับสมัคร'] as $key => $label)
            <button wire:click="switchTab('{{ $key }}')"
                    class="px-4 py-2 rounded-full text-sm font-semibold transition
                           {{ $tab === $key ? 'bg-orange-500 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Items --}}
    @if(count($items) === 0)
        <div class="text-center py-16">
            <div class="text-5xl mb-3">📭</div>
            <p class="text-gray-400 font-medium">ยังไม่มีรายการโปรด</p>
            <a href="{{ $tab === 'work' ? route('frontend.works') : route('frontend.recruits') }}"
               class="inline-block mt-4 px-6 py-2 bg-orange-500 hover:bg-orange-400 text-white rounded-lg text-sm font-semibold transition">
                เรียกดู{{ $tab === 'work' ? 'งาน' : 'ประกาศรับสมัคร' }}
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($items as $fav)
                @php $item = $fav['favoritable'] ?? null; @endphp
                @if($item)
                    <div class="bg-gray-900 rounded-xl p-4 flex items-center gap-4 group hover:bg-gray-800/70 transition">
                        {{-- Image --}}
                        <a href="{{ route($tab === 'work' ? 'frontend.works.show' : 'frontend.recruits.show', $item['id']) }}"
                           class="w-16 h-16 rounded-lg overflow-hidden bg-gray-800 flex-shrink-0">
                            <img src="{{ $item['primary_image'] ?? 'https://picsum.photos/seed/'.($item['id'] ?? 0).'/200/200' }}"
                                 class="w-full h-full object-cover" alt="">
                        </a>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <a href="{{ route($tab === 'work' ? 'frontend.works.show' : 'frontend.recruits.show', $item['id']) }}"
                               class="font-semibold text-white text-sm truncate block hover:text-orange-400 transition">
                                {{ $item['title'] ?? '-' }}
                            </a>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                @if($tab === 'work' && isset($item['price']))
                                    <span>💰 ฿{{ number_format($item['price']) }}</span>
                                @elseif($tab === 'recruit' && isset($item['budget']))
                                    <span>💰 ฿{{ number_format($item['budget']) }}</span>
                                @endif
                                @if(isset($item['avg_review_rating']) && $item['avg_review_rating'] > 0)
                                    <span>⭐ {{ number_format($item['avg_review_rating'], 1) }}</span>
                                @endif
                            </div>
                        </div>
                        {{-- Remove --}}
                        <button wire:click="removeFavorite({{ $fav['id'] }})"
                                wire:confirm="ลบจากรายการโปรด?"
                                class="px-3 py-1.5 text-xs bg-red-500/10 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/20 transition flex-shrink-0 opacity-0 group-hover:opacity-100">
                            🗑 ลบ
                        </button>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

</div>
