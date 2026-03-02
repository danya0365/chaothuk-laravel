<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input wire:model.live.debounce.400ms="search" type="text"
               placeholder="🔍 ค้นหาตำแหน่งงาน..."
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
            <option value="">ทุกประเภท</option>
            @foreach($workTypes as $wt)
                <option value="{{ $wt->id }}">{{ $wt->name }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading class="text-center py-8 text-gray-400">กำลังโหลด...</div>

    <div wire:loading.remove>
        @if($recruits->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📭</div>
                <p class="text-gray-400">ไม่พบผลลัพธ์</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @foreach($recruits as $recruit)
                    <a href="{{ route('frontend.recruits.show', $recruit->id) }}"
                       class="group bg-gray-900 rounded-xl p-4 hover:ring-2 hover:ring-orange-500/40 transition flex gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                            <img src="{{ $recruit->primary_image ?? 'https://picsum.photos/seed/r'.$recruit->id.'/200/200' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition" alt="">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-white line-clamp-1 group-hover:text-orange-400 transition">
                                {{ $recruit->title }}
                            </p>
                            <p class="text-green-400 font-bold text-sm">฿{{ number_format($recruit->budget) }}/เดือน</p>
                            <p class="text-gray-500 text-xs mt-1">📍 {{ $recruit->province?->name_th ?? '-' }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $recruit->recruit_status === 'stand-by' ? 'bg-green-500/20 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                                {{ match($recruit->recruit_status) {
                                    'stand-by' => '🟢 รับสมัคร',
                                    'busy'     => '🟡 ไม่ว่าง',
                                    'close'    => '🔴 ปิดรับ',
                                    default    => $recruit->recruit_status,
                                } }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
            {{ $recruits->links() }}
        @endif
    </div>

</div>
