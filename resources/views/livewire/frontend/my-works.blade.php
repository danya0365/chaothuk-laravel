<div class="max-w-6xl mx-auto px-3 sm:px-4 py-4 md:py-8">

    {{-- Header & Stats --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">💼 การจัดการงานของฉัน</h1>
                <p class="text-gray-400 text-sm md:text-base">จัดการบริการ, แก้ไขข้อมูล, และโปรโมทงานของคุณให้โดดเด่น</p>
            </div>
            <a href="{{ route('frontend.works.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl text-sm transition">
                ＋ สร้างงานใหม่
            </a>
        </div>

        {{-- Quick Stats Cards --}}
        <div class="grid grid-cols-3 gap-3 md:gap-4">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-gray-500 text-xs md:text-sm mb-1 font-semibold">งานที่เปิดบรรยายทั้งหมด</p>
                <div class="text-2xl md:text-3xl font-bold text-white">{{ $stats['total'] }} <span class="text-base text-gray-500 font-normal">งาน</span></div>
            </div>
            <div class="bg-green-900/20 border border-green-800/30 rounded-xl p-4">
                <p class="text-green-500/80 text-xs md:text-sm mb-1 font-semibold">อยู่ในช่วงโปรโมท (ฟีเจอร์)</p>
                <div class="text-2xl md:text-3xl font-bold text-green-400">{{ $stats['active_promotions'] }} <span class="text-base text-green-500/50 font-normal">งาน</span></div>
            </div>
            <div class="bg-red-900/10 border border-red-800/30 rounded-xl p-4">
                <p class="text-red-400/80 text-xs md:text-sm mb-1 font-semibold">ถูกใจสะสมทั้งหมด</p>
                <div class="text-2xl md:text-3xl font-bold text-red-400">{{ $stats['total_likes'] }} <span class="text-base text-red-400/50 font-normal">ครั้ง</span></div>
            </div>
        </div>
    </div>

    {{-- System Messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Works List --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
        @if($works->isEmpty())
            <div class="text-center py-16 px-4">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-bold text-white mb-2">คุณยังไม่มีงานที่เปิดให้บริการ</h3>
                <p class="text-gray-400 mb-6">เริ่มสร้างงานชิ้นแรกของคุณเพื่อเริ่มต้นรับรายได้กับ Chaothuk</p>
                <a href="{{ route('frontend.works.create') }}" class="inline-block px-6 py-3 bg-white text-black font-bold rounded-xl text-sm hover:scale-105 transition-transform">
                    สร้างงานเลย
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-800/60">
                @foreach($works as $work)
                    <div class="p-4 md:p-5 hover:bg-gray-800/30 transition flex flex-col lg:flex-row gap-4 lg:items-center">
                        
                        {{-- Thumbnail & Details --}}
                        <div class="flex items-start gap-4 flex-1">
                            <a href="{{ route('frontend.works.show', $work->id) }}" class="flex-shrink-0 group">
                                <div class="w-24 h-16 md:w-32 md:h-20 rounded-lg overflow-hidden relative bg-gray-800">
                                    <img src="{{ image_url($work->primary_image, 'https://picsum.photos/seed/'.$work->id.'/200/150') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                                    {{-- Status Indicator --}}
                                    <div class="absolute top-1 left-1">
                                        @php
                                            $statusDot = match($work->work_status) {
                                                'stand-by' => 'bg-green-500',
                                                'busy' => 'bg-yellow-500',
                                                'close' => 'bg-red-500',
                                                default => 'bg-gray-500',
                                            };
                                        @endphp
                                        <span class="block {{ $statusDot }} w-2.5 h-2.5 rounded-full ring-2 ring-gray-900"></span>
                                    </div>
                                </div>
                            </a>
                            <div class="min-w-0">
                                <a href="{{ route('frontend.works.show', $work->id) }}" class="font-semibold text-sm md:text-base text-white hover:text-orange-400 transition line-clamp-1 mb-1">
                                    {{ $work->title }}
                                </a>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-0.5">
                                    <span class="text-orange-400 font-bold text-sm">฿{{ number_format($work->price) }}</span>
                                    <span class="text-gray-500 text-xs">📍 {{ $work->province?->name_th ?? '-' }}</span>
                                    <span class="text-gray-500 text-xs flex items-center gap-1">⭐ {{ number_format($work->avg_review_rating,1) }} ({{ $work->like_count }} Likes)</span>
                                </div>

                                {{-- Feature Status Badge --}}
                                <div class="mt-2.5">
                                    @if(isset($work->activeFeature))
                                        <span class="inline-flex items-center gap-1.5 bg-green-500/10 text-green-400 px-2 py-1 rounded text-xs font-medium border border-green-500/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            ฟีเจอร์โฆษณาทำงานอยู่ (หมดอายุใน {{ \Carbon\Carbon::parse($work->activeFeature->end_at)->diffInDays(now()) + 1 }} วัน)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-gray-500 text-xs font-medium">
                                            แสดงผลปกติ
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Management Actions --}}
                        <div class="flex items-center gap-2 lg:ml-4 lg:flex-shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 border-gray-800">
                            {{-- Promote/Renew --}}
                            @if(isset($work->activeFeature))
                                <a href="{{ route('frontend.works.promote', $work->id) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-1 px-3 py-2 bg-green-600/80 hover:bg-green-500 text-white text-xs font-medium rounded-lg transition">
                                    ✨ ต่ออายุฟีเจอร์
                                </a>
                            @else
                                <a href="{{ route('frontend.works.promote', $work->id) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-1 px-3 py-2 bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-500 hover:to-amber-400 text-white text-xs font-medium rounded-lg transition shadow-lg shadow-orange-500/20">
                                    🚀 ดันฟีเจอร์
                                </a>
                            @endif
                            
                            {{-- Bookings (Orders) --}}
                            <a href="{{ route('frontend.works.bookings', $work->id) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-1 px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition" title="ดูรายการสั่งจอง / ออเดอร์">
                                📋 <span class="hidden sm:inline">คิวจอง</span>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('frontend.works.edit', $work->id) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-1 px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition">
                                ✏️ <span class="hidden sm:inline">แก้ไข</span>
                            </a>

                            {{-- Delete --}}
                            <button wire:click="deleteWork({{ $work->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบงานนี้? (ระวัง: หากมีคิวจองค้างอยู่จะไม่สามารถลบได้)" class="flex items-center justify-center w-8 h-8 md:w-[34px] md:h-[34px] bg-red-500/10 hover:bg-red-500/20 text-red-500 text-xs rounded-lg transition">
                                🗑
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $works->links() }}
    </div>

</div>