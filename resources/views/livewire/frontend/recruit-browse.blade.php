<div class="max-w-6xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    {{-- Header with Tabs + Create Button --}}
    <div class="flex items-center justify-between mb-4 md:mb-5">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-white">👷 รับสมัครงาน</h1>
        </div>
        @auth
        <a href="{{ route('frontend.recruits.create') }}"
           class="px-3 py-2 md:px-5 md:py-2.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl text-xs md:text-sm transition flex items-center gap-1 md:gap-1.5">
            ＋ สร้างประกาศใหม่
        </a>
        @endauth
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-4 md:mb-5 bg-gray-900 rounded-xl p-1 w-fit">
        <button wire:click="$set('tab', 'all')"
                class="px-3 py-1.5 md:px-5 md:py-2 rounded-lg text-xs md:text-sm font-semibold transition
                    {{ $tab === 'all' ? 'bg-green-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
            👷 ทั้งหมด
        </button>
        @auth
        <button wire:click="$set('tab', 'my')"
                class="px-3 py-1.5 md:px-5 md:py-2 rounded-lg text-xs md:text-sm font-semibold transition flex items-center gap-1 md:gap-1.5
                    {{ $tab === 'my' ? 'bg-green-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
            👤 ของฉัน
            @if($myRecruitsCount > 0)
                <span class="bg-white/20 text-[10px] px-1.5 py-0.5 rounded-full font-bold">{{ $myRecruitsCount }}</span>
            @endif
        </button>
        @endauth
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-2.5 md:gap-3 mb-3 md:mb-4">
        <input wire:model.live.debounce.400ms="search" type="text"
               placeholder="🔍 ค้นหาตำแหน่งงาน..."
               class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 md:px-4 md:py-2.5 text-white text-xs md:text-sm placeholder-gray-500 focus:outline-none focus:border-green-500 transition">

        <select wire:model.live="provinceId"
                class="bg-gray-800 border border-gray-700 rounded-xl px-2.5 py-2 md:px-3 md:py-2.5 text-gray-300 text-xs md:text-sm focus:outline-none focus:border-green-500 transition">
            <option value="">📍 ทุกจังหวัด</option>
            @foreach($provinces as $p)
                <option value="{{ $p->id }}">{{ $p->name_th }}</option>
            @endforeach
        </select>

        <select wire:model.live="workTypeId"
                class="bg-gray-800 border border-gray-700 rounded-xl px-2.5 py-2 md:px-3 md:py-2.5 text-gray-300 text-xs md:text-sm focus:outline-none focus:border-green-500 transition">
            <option value="">🚛 ทุกประเภท</option>
            @foreach($workTypes as $wt)
                <option value="{{ $wt->id }}">{{ $wt->title }}</option>
            @endforeach
        </select>

        <select wire:model.live="sortBy"
                class="bg-gray-800 border border-gray-700 rounded-xl px-2.5 py-2 md:px-3 md:py-2.5 text-gray-300 text-xs md:text-sm focus:outline-none focus:border-green-500 transition">
            <option value="latest">ล่าสุด</option>
            <option value="budget_desc">งบสูง → ต่ำ</option>
            <option value="budget_asc">งบต่ำ → สูง</option>
            <option value="distance" x-show="$wire.userLat" disabled>📍 ระยะทางใกล้สุด</option>
        </select>

        <div x-data="{
            handleLocationPicked(e) {
                @this.set('userLat', e.detail.lat);
                @this.set('userLng', e.detail.lng);
                @this.set('sortBy', 'distance');
            }
        }" @location-picked.window="handleLocationPicked" class="flex gap-2">
            
            <x-location-picker
                wire:model.lat="userLat"
                wire:model.lng="userLng"
                label="📍 ใกล้ฉัน"
                class="h-[38px] md:h-[46px] text-xs md:text-sm {{ $sortBy === 'distance' ? 'bg-green-500/20 text-green-400 border-green-500/50 ring-1 ring-green-500/50' : '' }}" />

            @if($userLat && $userLng)
                <button type="button" wire:click="$set('userLat', null); $set('userLng', null); $set('sortBy', 'latest')"
                        class="h-[38px] md:h-[46px] px-3 md:px-4 bg-gray-800 border border-gray-700 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/50 text-gray-400 rounded-xl transition flex items-center justify-center font-bold"
                        title="ยกเลิกระยะทาง">
                    ✕
                </button>
            @endif
        </div>
    </div>

    {{-- Count --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-gray-400 text-sm">
            {{ $recruits->total() }} ประกาศ
            @if($tab === 'my')
                <span class="text-green-400 text-xs">(ของฉัน)</span>
            @endif
        </p>
    </div>

    {{-- Loading --}}
    <div wire:loading class="text-center py-8 text-gray-400">กำลังโหลด...</div>

    {{-- Grid View --}}
    <div wire:loading.remove>
        @if($recruits->isEmpty())
            <div class="text-center py-12 md:py-20">
                <div class="text-5xl md:text-6xl mb-3 md:mb-4">📭</div>
                @if($tab === 'my')
                    <p class="text-gray-400 mb-3 md:mb-4 text-sm md:text-base">คุณยังไม่มีประกาศหางาน</p>
                    <a href="{{ route('frontend.recruits.create') }}"
                       class="inline-block px-4 py-2 md:px-6 md:py-2.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition text-xs md:text-sm">
                        ＋ สร้างประกาศแรกของคุณ
                    </a>
                @else
                    <p class="text-gray-400 text-sm md:text-base">ไม่พบผลลัพธ์</p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @foreach($recruits as $recruit)
                    <div class="group bg-gray-900 rounded-xl p-3 md:p-4 hover:ring-2 hover:ring-green-500/40 transition relative">
                        <a href="{{ route('frontend.recruits.show', $recruit->id) }}" class="flex gap-3 md:gap-4">
                            <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                                <img src="{{ $recruit->primary_image ?? 'https://picsum.photos/seed/r'.$recruit->id.'/200/200' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-white line-clamp-1 group-hover:text-green-400 transition text-sm md:text-base">
                                    {{ $recruit->title }}
                                </p>
                                <p class="text-green-400 font-bold text-xs md:text-sm">฿{{ number_format($recruit->budget) }}/ครั้ง</p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <p class="text-gray-500 text-[10px] md:text-xs">📍 {{ $recruit->province?->name_th ?? '-' }}</p>
                                    @if(isset($recruit->distance))
                                        <p class="text-green-400 text-[9px] md:text-[10px] font-semibold">
                                            @if($recruit->distance < 1)
                                                ({{ number_format($recruit->distance * 1000) }} ม.)
                                            @else
                                                ({{ number_format($recruit->distance, 1) }} กม.)
                                            @endif
                                        </p>
                                    @endif
                                    @if($recruit->workType)
                                        <span class="text-gray-600 text-[9px] md:text-[10px]">{{ $recruit->workType->name }}</span>
                                    @endif
                                </div>
                                <span class="inline-block mt-0.5 md:mt-1 px-1.5 md:px-2 py-0.5 rounded-full text-[9px] md:text-[10px] font-semibold
                                    {{ match($recruit->recruit_status) {
                                        'stand-by' => 'bg-green-500/20 text-green-400',
                                        'busy'     => 'bg-yellow-500/20 text-yellow-400',
                                        'close'    => 'bg-red-500/20 text-red-400',
                                        default    => 'bg-gray-700 text-gray-400',
                                    } }}">
                                    {{ match($recruit->recruit_status) {
                                        'stand-by' => '🟢 รับสมัคร',
                                        'busy'     => '🟡 ไม่ว่าง',
                                        'close'    => '🔴 ปิดรับ',
                                        default    => $recruit->recruit_status,
                                    } }}
                                </span>
                            </div>
                        </a>

                        {{-- Owner quick actions --}}
                        @if($tab === 'my' && auth()->check() && auth()->id() === $recruit->author_id)
                        <div class="flex items-center gap-1.5 md:gap-2 mt-2 md:mt-3 pt-2 md:pt-3 border-t border-gray-800">
                            <a href="{{ route('frontend.recruits.show', $recruit->id) }}"
                               class="flex-1 text-center text-[10px] md:text-xs py-1 md:py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition">
                                👁 ดู
                            </a>
                            <a href="{{ route('frontend.recruits.edit', $recruit->id) }}"
                               class="flex-1 text-center text-[10px] md:text-xs py-1 md:py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition">
                                ✏️ แก้ไข
                            </a>
                            <button wire:click="deleteRecruit({{ $recruit->id }})"
                                    wire:confirm="ลบประกาศนี้?"
                                    class="text-[10px] md:text-xs py-1 md:py-1.5 px-2 md:px-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition">
                                🗑 ลบ
                            </button>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
            {{ $recruits->links() }}
        @endif
    </div>

</div>
