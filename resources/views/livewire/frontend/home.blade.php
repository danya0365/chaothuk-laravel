<div class="max-w-6xl mx-auto px-4 py-6 space-y-10">

    {{-- Hero --}}
    <section class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-orange-600 via-orange-500 to-amber-400 p-8 md:p-12">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-black text-white mb-2">🚛 Chaothuk</h1>
            <p class="text-orange-100 text-lg mb-6">แพลตฟอร์มขนส่งและรับสมัครงาน สำหรับคนไทย</p>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('frontend.works') }}"
                   class="px-6 py-2.5 bg-white text-orange-600 font-bold rounded-full hover:bg-orange-50 transition shadow-lg">
                    ดูงานทั้งหมด
                </a>
                <a href="{{ route('frontend.recruits') }}"
                   class="px-6 py-2.5 bg-orange-900/30 text-white font-bold rounded-full hover:bg-orange-900/50 transition border border-white/20">
                    หาคน / รับสมัคร
                </a>
            </div>
        </div>
        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 text-[120px] flex items-center justify-center">🚛</div>
    </section>

    {{-- Latest Works --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">📦 งานล่าสุด</h2>
            <a href="{{ route('frontend.works') }}" class="text-orange-400 text-sm hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($latestWorks as $work)
                <a href="{{ route('frontend.works.show', $work['id']) }}"
                   class="group bg-gray-900 rounded-xl overflow-hidden hover:ring-2 hover:ring-orange-500/50 transition">
                    <div class="aspect-video bg-gray-800 overflow-hidden">
                        <img src="{{ $work['primary_image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/400/300' }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $work['title'] }}">
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-sm text-white line-clamp-1">{{ $work['title'] }}</p>
                        <p class="text-orange-400 font-bold text-sm">฿{{ number_format($work['price'] ?? 0) }}</p>
                        <p class="text-gray-500 text-xs mt-1">📍 {{ $work['province']['name_th'] ?? '-' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Latest Recruits --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">👷 รับสมัครงาน</h2>
            <a href="{{ route('frontend.recruits') }}" class="text-orange-400 text-sm hover:underline">ดูทั้งหมด →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($latestRecruits as $recruit)
                <a href="{{ route('frontend.recruits.show', $recruit['id']) }}"
                   class="flex gap-4 bg-gray-900 rounded-xl p-4 hover:ring-2 hover:ring-orange-500/50 transition group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                        <img src="{{ $recruit['primary_image'] ?? 'https://picsum.photos/seed/r'.$recruit['id'].'/200/200' }}"
                             class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-white line-clamp-1 group-hover:text-orange-400 transition">{{ $recruit['title'] }}</p>
                        <p class="text-green-400 font-bold text-sm">฿{{ number_format($recruit['budget'] ?? 0) }}/เดือน</p>
                        <p class="text-gray-500 text-xs">📍 {{ $recruit['province']['name_th'] ?? '-' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

</div>
