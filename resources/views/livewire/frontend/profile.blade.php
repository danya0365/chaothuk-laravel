<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- Profile card --}}
    <div class="bg-gray-900 rounded-2xl p-6 mb-6 flex items-center gap-4">
        <img src="{{ auth()->user()->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'U').'&background=f97316&color=fff' }}"
             class="w-20 h-20 rounded-full object-cover ring-4 ring-orange-500/30" alt="">
        <div class="flex-1">
            <h1 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h1>
            <p class="text-gray-400">{{ auth()->user()->email }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="px-4 py-2 text-sm text-gray-400 border border-gray-700 rounded-full hover:border-red-500 hover:text-red-400 transition">
                ออกจากระบบ
            </button>
        </form>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-gray-900 p-1 rounded-xl overflow-x-auto">
        @foreach([
            ['key'=>'info',     'label'=>'👤 ข้อมูล'],
            ['key'=>'works',    'label'=>'📦 งานของฉัน'],
            ['key'=>'recruits', 'label'=>'👷 รับสมัครของฉัน'],
            ['key'=>'bookings', 'label'=>'📋 การจอง'],
            ['key'=>'reviews',  'label'=>'⭐ รีวิวของฉัน'],
        ] as $tabItem)
            <button wire:click="switchTab('{{ $tabItem['key'] }}')"
                    class="whitespace-nowrap px-4 py-2 rounded-lg text-sm font-semibold transition
                           {{ $tab === $tabItem['key'] ? 'bg-orange-500 text-white' : 'text-gray-400 hover:text-white' }}">
                {{ $tabItem['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Tab content --}}
    <div wire:loading class="text-center py-8 text-gray-400">กำลังโหลด...</div>
    <div wire:loading.remove>

    @if($tab === 'info')
        <div class="bg-gray-900 rounded-xl p-4 space-y-3">
            @foreach([
                ['label'=>'ชื่อ', 'value'=>auth()->user()->first_name.' '.auth()->user()->last_name],
                ['label'=>'อีเมล', 'value'=>auth()->user()->email],
                ['label'=>'เบอร์โทร', 'value'=>auth()->user()->mobile_phone ?? '-'],
                ['label'=>'สมัครเมื่อ', 'value'=>auth()->user()->created_at?->diffForHumans()],
            ] as $row)
                <div class="flex justify-between py-2 border-b border-gray-800 last:border-0">
                    <span class="text-gray-400 text-sm">{{ $row['label'] }}</span>
                    <span class="text-white text-sm font-medium">{{ $row['value'] }}</span>
                </div>
            @endforeach
        </div>

    @elseif($tab === 'works')
        @forelse($myWorks ?? [] as $work)
            <a href="{{ route('frontend.works.show', $work['id']) }}"
               class="flex gap-4 bg-gray-900 rounded-xl p-4 mb-3 hover:ring-2 hover:ring-orange-500/30 transition group">
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                    <img src="{{ $work['primary_image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/200/200' }}"
                         class="w-full h-full object-cover" alt="">
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-orange-400 transition">{{ $work['title'] }}</p>
                    <p class="text-orange-400 font-bold text-sm">฿{{ number_format($work['price'] ?? 0) }}</p>
                    <p class="text-gray-500 text-xs">⭐ {{ $work['avg_review_rating'] ?? 0 }} · ❤️ {{ $work['like_count'] ?? 0 }}</p>
                </div>
            </a>
        @empty
            <p class="text-gray-500 text-center py-12">ยังไม่มีงาน</p>
        @endforelse

    @elseif($tab === 'recruits')
        @forelse($myRecruits ?? [] as $recruit)
            <a href="{{ route('frontend.recruits.show', $recruit['id']) }}"
               class="flex gap-4 bg-gray-900 rounded-xl p-4 mb-3 hover:ring-2 hover:ring-orange-500/30 transition group">
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                    <img src="{{ $recruit['primary_image'] ?? 'https://picsum.photos/seed/r'.$recruit['id'].'/200/200' }}"
                         class="w-full h-full object-cover" alt="">
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-orange-400 transition">{{ $recruit['title'] }}</p>
                    <p class="text-green-400 font-bold text-sm">฿{{ number_format($recruit['budget'] ?? 0) }}/เดือน</p>
                </div>
            </a>
        @empty
            <p class="text-gray-500 text-center py-12">ยังไม่มีประกาศรับสมัคร</p>
        @endforelse

    @elseif($tab === 'bookings')
        <h3 class="font-bold text-white mb-3">การจองงาน</h3>
        @forelse($myWorkBookings ?? [] as $b)
            <div class="bg-gray-900 rounded-xl p-4 mb-3">
                <div class="flex justify-between items-start">
                    <p class="font-semibold text-white">{{ $b['work']['title'] ?? '-' }}</p>
                    <span class="px-2 py-0.5 rounded-full text-xs
                        {{ str_contains($b['booking_status'], 'confirm') ? 'bg-green-500/20 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                        {{ $b['booking_status'] }}
                    </span>
                </div>
                <p class="text-gray-500 text-xs mt-1">📅 {{ $b['booking_date'] ?? '-' }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">ยังไม่มีการจองงาน</p>
        @endforelse

        <h3 class="font-bold text-white mb-3 mt-6">การสมัครงาน</h3>
        @forelse($myRecruitBookings ?? [] as $b)
            <div class="bg-gray-900 rounded-xl p-4 mb-3">
                <div class="flex justify-between items-start">
                    <p class="font-semibold text-white">{{ $b['recruit']['title'] ?? '-' }}</p>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-gray-700 text-gray-400">{{ $b['booking_status'] }}</span>
                </div>
                <p class="text-gray-500 text-xs mt-1">📅 {{ $b['booking_date'] ?? '-' }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">ยังไม่มีการสมัครงาน</p>
        @endforelse

    @elseif($tab === 'reviews')
        @forelse($myReviews ?? [] as $review)
            <div class="bg-gray-900 rounded-xl p-4 mb-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-yellow-400">{{ str_repeat('★', $review['rating'] ?? 0) }}</span>
                    <span class="text-gray-500 text-xs">{{ \Illuminate\Support\Carbon::parse($review['created_at'])->diffForHumans() }}</span>
                </div>
                @if($review['title'] ?? null)
                    <p class="font-semibold text-white text-sm">{{ $review['title'] }}</p>
                @endif
                <p class="text-gray-300 text-sm">{{ $review['content'] }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-center py-12">ยังไม่มีรีวิว</p>
        @endforelse
    @endif

    </div>

</div>
