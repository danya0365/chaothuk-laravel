<div class="max-w-4xl mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-white">🎨 ผลงานของฉัน</h1>
        <a href="{{ route('frontend.portfolios.create') }}"
           class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-lg text-sm transition">
            + เพิ่มผลงาน
        </a>
    </div>

    @if($flashMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $flashMessage }}
        </div>
    @endif

    @if(count($portfolios) === 0)
        <div class="text-center py-16">
            <div class="text-5xl mb-3">🎨</div>
            <p class="text-gray-400 font-medium">ยังไม่มีผลงาน</p>
            <a href="{{ route('frontend.portfolios.create') }}"
               class="inline-block mt-4 px-6 py-2 bg-orange-500 hover:bg-orange-400 text-white rounded-lg text-sm font-semibold transition">
                เพิ่มผลงานแรก
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($portfolios as $portfolio)
                <div class="bg-gray-900 rounded-xl overflow-hidden group hover:ring-1 hover:ring-orange-500/30 transition">
                    {{-- Gallery --}}
                    @php $images = $portfolio['images'] ?? []; @endphp
                    @if(count($images) > 0)
                        <div class="h-48 overflow-hidden bg-gray-800">
                            <img src="{{ $images[0] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                        </div>
                    @else
                        <div class="h-48 bg-gray-800 flex items-center justify-center text-gray-600 text-4xl">🖼</div>
                    @endif
                    {{-- Info --}}
                    <div class="p-4">
                        <h3 class="font-semibold text-white text-sm truncate">{{ $portfolio['title'] }}</h3>
                        @if(isset($portfolio['work_type']))
                            <span class="text-xs text-orange-400 mt-1 block">{{ $portfolio['work_type']['title'] ?? $portfolio['work_type']['name'] ?? '' }}</span>
                        @endif
                        <p class="text-gray-500 text-xs mt-2 line-clamp-2">{{ $portfolio['description'] ?? '' }}</p>
                        <div class="flex items-center gap-2 mt-3">
                            <a href="{{ route('frontend.portfolios.edit', $portfolio['id']) }}"
                               class="px-3 py-1.5 text-xs bg-gray-800 text-gray-300 hover:text-white border border-gray-700 rounded-lg hover:border-orange-500 transition">
                                ✏️ แก้ไข
                            </a>
                            <button wire:click="deletePortfolio({{ $portfolio['id'] }})"
                                    wire:confirm="ลบผลงานนี้?"
                                    class="px-3 py-1.5 text-xs bg-red-500/10 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/20 transition">
                                🗑 ลบ
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
