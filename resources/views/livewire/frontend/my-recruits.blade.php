<div class="max-w-6xl mx-auto px-4 py-8">
    
    {{-- Header & Stats --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-2">📝 ประกาศงานของฉัน</h1>
            <p class="text-gray-400 mt-1">จัดการและติดตามสถานะประกาศรับสมัครงานของคุณ</p>
        </div>
        <div class="flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-2xl p-4 shadow-lg">
            <div class="text-center px-4">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">ทั้งหมด</p>
                <p class="text-2xl font-black text-white">{{ count($recruits) ? $stats['total'] : 0 }}</p>
            </div>
            <div class="w-px h-10 bg-gray-800"></div>
            <div class="text-center px-4">
                <a href="{{ route('frontend.recruits.create') }}" class="px-5 py-2.5 bg-green-500 hover:bg-green-400 text-white font-bold rounded-xl transition shadow-md whitespace-nowrap">
                    ＋ สร้างประกาศ
                </a>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Recruits Grid --}}
    @if(empty($recruits) || $recruits->isEmpty())
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-16 text-center">
            <div class="text-6xl mb-6">📭</div>
            <h3 class="text-xl font-bold text-white mb-2">ยังไม่มีประกาศงาน</h3>
            <p class="text-gray-400 mb-8 max-w-sm mx-auto">เริ่มต้นสร้างประกาศรับสมัครงานแรกของคุณเพื่อให้ช่างผู้เชี่ยวชาญเห็น</p>
            <a href="{{ route('frontend.recruits.create') }}" class="inline-block px-8 py-3 bg-green-500 hover:bg-green-400 text-white font-bold rounded-xl transition shadow-lg hover:shadow-green-500/20">
                สร้างประกาศงาน
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recruits as $recruit)
                <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden hover:border-green-500/30 transition group flex flex-col">
                    <a href="{{ route('frontend.recruits.show', $recruit->id) }}" class="block p-4 border-b border-gray-800/50 relative">
                        {{-- Status Badge --}}
                        <div class="absolute top-4 right-4">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold shadow-md
                                {{ match($recruit->recruit_status) {
                                    'stand-by' => 'bg-green-500/20 text-green-400 border border-green-500/30',
                                    'busy'     => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
                                    'close'    => 'bg-red-500/20 text-red-400 border border-red-500/30',
                                    default    => 'bg-gray-700 text-gray-400',
                                } }}">
                                {{ match($recruit->recruit_status) {
                                    'stand-by' => '🟢 เปิดรับ',
                                    'busy'     => '🟡 คุยอยู่',
                                    'close'    => '🔴 ปิดรับ',
                                    default    => $recruit->recruit_status,
                                } }}
                            </span>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-800 flex-shrink-0">
                                <img src="{{ image_url($recruit->primary_image, 'https://picsum.photos/seed/r'.$recruit->id.'/200/200') }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="">
                            </div>
                            <div class="flex-1 min-w-0 pr-16">
                                <h3 class="font-bold text-white text-base leading-tight group-hover:text-green-400 transition mb-1 line-clamp-2">
                                    {{ $recruit->title }}
                                </h3>
                                <p class="text-green-400 font-bold text-sm">฿{{ number_format($recruit->budget) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mt-3 flex-wrap">
                            @if($recruit->province)
                                <span class="text-gray-500 text-xs">📍 {{ $recruit->province->name_th }}</span>
                            @endif
                            @if($recruit->workType)
                                <span class="bg-gray-800 text-gray-400 px-2 py-0.5 rounded text-[10px]">{{ $recruit->workType->name }}</span>
                            @endif
                        </div>
                    </a>

                    {{-- Actions Container --}}
                    <div class="p-3 bg-gray-900 grid grid-cols-2 gap-2 mt-auto">
                        <a href="{{ route('frontend.recruits.edit', $recruit->id) }}"
                           class="flex justify-center items-center py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded-xl transition text-sm">
                            ✏️ แก้ไข
                        </a>
                        <button wire:click="deleteRecruit({{ $recruit->id }})"
                                wire:confirm="คุณแน่ใจหรือไม่ที่จะลบประกาศนี้?"
                                class="flex justify-center items-center py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-medium rounded-xl transition text-sm">
                            🗑 ลบ
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $recruits->links() }}
        </div>
    @endif
</div>
