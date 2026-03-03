<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- Search input --}}
    <div class="mb-6">
        <form wire:submit="search" class="flex gap-3">
            <input wire:model="query" type="text" placeholder="ค้นหางาน, รถบรรทุก, จังหวัด..."
                   autofocus
                   class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-5 py-3 text-white text-lg placeholder-gray-500 focus:outline-none focus:border-orange-500 transition">
            <button type="submit"
                    class="px-6 py-3 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl transition">
                ค้นหา
            </button>
        </form>
    </div>

    {{-- Type toggle --}}
    <div class="flex gap-2 mb-6">
        <button wire:click="setType('works')"
                class="px-5 py-2 rounded-full font-semibold text-sm transition
                       {{ $type === 'works' ? 'bg-orange-500 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            📦 งาน
        </button>
        <button wire:click="setType('recruits')"
                class="px-5 py-2 rounded-full font-semibold text-sm transition
                       {{ $type === 'recruits' ? 'bg-orange-500 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            👷 รับสมัคร
        </button>
    </div>

    {{-- No search yet --}}
    @if(!$searched)
        <div class="text-center py-20 text-gray-500">
            <div class="text-5xl mb-4">🔍</div>
            <p>พิมพ์คำค้นหาแล้วกด Enter</p>
        </div>
    @elseif(empty($results))
        <div class="text-center py-20 text-gray-500">
            <div class="text-5xl mb-4">📭</div>
            <p>ไม่พบผลลัพธ์สำหรับ "<strong class="text-white">{{ $query }}</strong>"</p>
        </div>
    @else
        <p class="text-gray-400 text-sm mb-4">พบ {{ count($results) }} ผลลัพธ์สำหรับ "<strong class="text-white">{{ $query }}</strong>"</p>

        @if($type === 'works')
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($results as $item)
                    <a href="{{ route('frontend.works.show', $item['id']) }}"
                       class="group bg-gray-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-orange-500/40 transition">
                        <div class="aspect-video bg-gray-800 overflow-hidden">
                            <img src="{{ $item['primary_image'] ?? 'https://picsum.photos/seed/'.$item['id'].'/400/300' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition" alt="">
                        </div>
                        <div class="p-3">
                            <p class="font-semibold text-sm text-white line-clamp-1 group-hover:text-orange-400">{{ $item['title'] }}</p>
                            <p class="text-orange-400 font-bold text-sm">฿{{ number_format($item['price'] ?? 0) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="space-y-3">
                @foreach($results as $item)
                    <a href="{{ route('frontend.recruits.show', $item['id']) }}"
                       class="flex gap-4 bg-gray-900 rounded-xl p-4 hover:ring-2 hover:ring-orange-500/40 transition group">
                        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                            <img src="{{ $item['primary_image'] ?? 'https://picsum.photos/seed/r'.$item['id'].'/200/200' }}"
                                 class="w-full h-full object-cover" alt="">
                        </div>
                        <div>
                            <p class="font-semibold text-white group-hover:text-orange-400 transition line-clamp-1">{{ $item['title'] }}</p>
                            <p class="text-green-400 text-sm font-bold">฿{{ number_format($item['budget'] ?? 0) }}/เดือน</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endif

</div>
