<div class="max-w-4xl mx-auto px-4 py-6"
     x-data="{
         uploading: false,
         uploadError: '',
         async uploadAvatar(file) {
             this.uploading = true;
             this.uploadError = '';
             const formData = new FormData();
             formData.append('image', file);
             try {
                 const res = await fetch('/api/upload/avatar', {
                     method: 'POST',
                     headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                     body: formData,
                 });
                 const json = await res.json();
                 if (json.status && json.data) {
                     const url = json.data.resize || json.data.original;
                     @this.set('profileImage', url);
                 } else {
                     this.uploadError = json.message || 'อัพโหลดไม่สำเร็จ';
                 }
             } catch (e) {
                 this.uploadError = 'เกิดข้อผิดพลาดในการอัพโหลด';
             }
             this.uploading = false;
         }
     }">

    {{-- ═══════════════════════════════════════════════════════════════════
         HERO PROFILE CARD
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-br from-gray-900 via-gray-900 to-orange-500/5 rounded-2xl p-6 mb-6 border border-gray-800/50">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
            {{-- Avatar --}}
            <div class="relative group flex-shrink-0">
                <img src="{{ $profileImage ?: 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'U').'&background=f97316&color=fff&size=200' }}"
                     class="w-24 h-24 rounded-2xl object-cover ring-4 ring-orange-500/30 shadow-lg shadow-orange-500/10" alt="Avatar">
                @if($editing)
                <label class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-2xl cursor-pointer opacity-0 group-hover:opacity-100 transition"
                       :class="uploading ? 'opacity-100' : ''">
                    <template x-if="!uploading"><span class="text-white text-xs font-semibold">📷 เปลี่ยน</span></template>
                    <template x-if="uploading"><span class="text-orange-400 text-xs">⏳</span></template>
                    <input type="file" accept="image/*" class="hidden"
                           @change="if ($event.target.files[0]) { uploadAvatar($event.target.files[0]); $event.target.value = '' }">
                </label>
                @endif
            </div>

            {{-- Name & Info --}}
            <div class="flex-1 text-center sm:text-left min-w-0">
                <h1 class="text-2xl font-black text-white">{{ auth()->user()->name }}</h1>
                <p class="text-gray-400 text-sm mt-0.5">{{ auth()->user()->email }}</p>
                <div class="flex items-center gap-3 mt-2 justify-center sm:justify-start flex-wrap text-xs">
                    @if(auth()->user()->mobile_phone)
                    <span class="flex items-center gap-1 text-gray-500">📱 {{ auth()->user()->mobile_phone }}</span>
                    @endif
                    @if(auth()->user()->location)
                    <span class="flex items-center gap-1 text-gray-500">📍 {{ auth()->user()->location }}</span>
                    @endif
                    <span class="flex items-center gap-1 text-gray-600">📅 สมาชิกตั้งแต่ {{ auth()->user()->created_at?->format('M Y') }}</span>
                </div>
                @if(auth()->user()->biography)
                <p class="text-gray-400 text-sm mt-2 line-clamp-2">{{ auth()->user()->biography }}</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                @if(!$editing)
                <button wire:click="toggleEdit"
                        class="px-4 py-2 text-sm font-semibold text-orange-400 bg-orange-500/10 border border-orange-500/30 rounded-xl hover:bg-orange-500/20 transition">
                    ✏️ แก้ไขโปรไฟล์
                </button>
                @endif
                <a href="{{ route('frontend.reputation', auth()->id()) }}"
                   class="px-4 py-2 text-sm text-gray-400 border border-gray-700 rounded-xl hover:border-orange-500/50 hover:text-white transition">
                    📊 ชื่อเสียง
                </a>
            </div>
        </div>

        {{-- Upload error --}}
        <template x-if="uploadError"><p class="text-red-400 text-xs mt-2" x-text="uploadError"></p></template>

        {{-- Save message --}}
        @if($saveMessage)
            <div class="mt-4 bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-lg px-4 py-2">{{ $saveMessage }}</div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         EDIT PROFILE FORM
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($editing)
    <div class="bg-gray-900 rounded-2xl p-5 mb-6 border border-orange-500/20">
        <h2 class="font-bold text-white mb-4 flex items-center gap-2">✏️ แก้ไขข้อมูลส่วนตัว</h2>

        <form wire:submit="saveProfile" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">ชื่อจริง <span class="text-red-400">*</span></label>
                    <input wire:model="firstName" type="text" placeholder="ชื่อ"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
                    @error('firstName')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">นามสกุล <span class="text-red-400">*</span></label>
                    <input wire:model="lastName" type="text" placeholder="นามสกุล"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
                    @error('lastName')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-400 text-xs mb-1">ชื่อที่แสดง <span class="text-red-400">*</span></label>
                <input wire:model="name" type="text" placeholder="ชื่อที่จะแสดงในระบบ"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">📱 เบอร์โทรศัพท์</label>
                    <input wire:model="mobilePhone" type="tel" placeholder="0xx-xxx-xxxx"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
                    @error('mobilePhone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">📍 ที่อยู่ / จังหวัด</label>
                    <input wire:model="location" type="text" placeholder="เช่น กรุงเทพฯ"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 transition">
                    @error('location')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-gray-400 text-xs mb-1">📝 แนะนำตัว</label>
                <textarea wire:model="biography" rows="3" placeholder="เล่าเกี่ยวกับตัวคุณ ทักษะ ประสบการณ์..."
                          class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500 resize-none transition"></textarea>
                @error('biography')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Preview uploaded avatar --}}
            @if($profileImage)
            <div class="flex items-center gap-3">
                <img src="{{ $profileImage }}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-orange-500/30" alt="preview">
                <span class="text-gray-500 text-xs">รูปโปรไฟล์ปัจจุบัน</span>
            </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        class="px-6 py-2.5 bg-orange-500 hover:bg-orange-400 text-white font-bold rounded-xl transition text-sm">
                    <span wire:loading.remove wire:target="saveProfile">💾 บันทึก</span>
                    <span wire:loading wire:target="saveProfile">⏳ กำลังบันทึก...</span>
                </button>
                <button type="button" wire:click="toggleEdit"
                        class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl transition text-sm ring-1 ring-gray-700">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         TABS
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="flex gap-1 mb-6 bg-gray-900 p-1 rounded-xl overflow-x-auto">
        @foreach([
            ['key'=>'info',       'label'=>'👤 ข้อมูล'],
            ['key'=>'works',      'label'=>'📦 งาน'],
            ['key'=>'recruits',   'label'=>'👷 รับสมัคร'],
            ['key'=>'portfolios', 'label'=>'🎨 ผลงาน'],
            ['key'=>'bookings',   'label'=>'📋 การจอง'],
            ['key'=>'reviews',    'label'=>'⭐ รีวิว'],
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
        <div class="bg-gray-900 rounded-2xl p-5 space-y-0">
            @foreach([
                ['icon'=>'👤', 'label'=>'ชื่อ-นามสกุล', 'value'=>(auth()->user()->first_name ?? '').' '.(auth()->user()->last_name ?? '')],
                ['icon'=>'🏷️', 'label'=>'ชื่อที่แสดง',    'value'=>auth()->user()->name],
                ['icon'=>'✉️', 'label'=>'อีเมล',         'value'=>auth()->user()->email],
                ['icon'=>'📱', 'label'=>'เบอร์โทร',       'value'=>auth()->user()->mobile_phone ?? '-'],
                ['icon'=>'📍', 'label'=>'ที่อยู่',         'value'=>auth()->user()->location ?? '-'],
                ['icon'=>'📝', 'label'=>'แนะนำตัว',       'value'=>auth()->user()->biography ?? '-'],
                ['icon'=>'📅', 'label'=>'สมัครเมื่อ',     'value'=>auth()->user()->created_at?->format('d M Y').' ('.auth()->user()->created_at?->diffForHumans().')'],
            ] as $row)
                <div class="flex items-start py-3 border-b border-gray-800 last:border-0 gap-3">
                    <span class="text-lg flex-shrink-0">{{ $row['icon'] }}</span>
                    <div class="flex-1 min-w-0">
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">{{ $row['label'] }}</span>
                        <span class="text-white text-sm font-medium mt-0.5 block">{{ $row['value'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

    @elseif($tab === 'works')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($myWorks ?? [] as $work)
                <a href="{{ route('frontend.works.show', $work['id']) }}"
                   class="flex gap-3 bg-gray-900 rounded-xl p-4 hover:ring-2 hover:ring-orange-500/30 transition group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                        <img src="{{ $work['primary_image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/200/200' }}"
                             class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white group-hover:text-orange-400 transition truncate text-sm">{{ $work['title'] }}</p>
                        <p class="text-orange-400 font-bold text-sm">฿{{ number_format($work['price'] ?? 0) }}</p>
                        <p class="text-gray-500 text-xs">⭐ {{ number_format($work['avg_review_rating'] ?? 0, 1) }} · ❤️ {{ $work['like_count'] ?? 0 }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-12">
                    <div class="text-4xl mb-2">📦</div>
                    <p class="text-gray-500">ยังไม่มีงาน</p>
                    <a href="{{ route('frontend.works.create') }}" class="inline-block mt-3 px-4 py-2 bg-orange-500 text-white font-semibold text-sm rounded-lg hover:bg-orange-400 transition">+ สร้างงานใหม่</a>
                </div>
            @endforelse
        </div>

    @elseif($tab === 'recruits')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($myRecruits ?? [] as $recruit)
                <a href="{{ route('frontend.recruits.show', $recruit['id']) }}"
                   class="flex gap-3 bg-gray-900 rounded-xl p-4 hover:ring-2 hover:ring-orange-500/30 transition group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                        <img src="{{ $recruit['primary_image'] ?? 'https://picsum.photos/seed/r'.$recruit['id'].'/200/200' }}"
                             class="w-full h-full object-cover" alt="">
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white group-hover:text-orange-400 transition truncate text-sm">{{ $recruit['title'] }}</p>
                        <p class="text-green-400 font-bold text-sm">฿{{ number_format($recruit['budget'] ?? 0) }}/เดือน</p>
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-12">
                    <div class="text-4xl mb-2">👷</div>
                    <p class="text-gray-500">ยังไม่มีประกาศรับสมัคร</p>
                </div>
            @endforelse
        </div>

    @elseif($tab === 'portfolios')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($myPortfolios ?? [] as $portfolio)
                <a href="{{ route('frontend.portfolios.show', $portfolio['id']) }}"
                   class="bg-gray-900 rounded-xl overflow-hidden group hover:ring-1 hover:ring-orange-500/30 transition">
                    @php $pImages = $portfolio['images'] ?? []; @endphp
                    @if(count($pImages) > 0)
                        <div class="h-40 overflow-hidden bg-gray-800">
                            <img src="{{ $pImages[0] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                        </div>
                    @else
                        <div class="h-40 bg-gray-800 flex items-center justify-center text-gray-600 text-4xl">🖼</div>
                    @endif
                    <div class="p-3">
                        <p class="font-semibold text-white text-sm truncate">{{ $portfolio['title'] }}</p>
                        @if(isset($portfolio['work_type']))
                            <span class="text-xs text-orange-400">{{ $portfolio['work_type']['title'] ?? '' }}</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-12">
                    <div class="text-4xl mb-2">🎨</div>
                    <p class="text-gray-500">ยังไม่มีผลงาน</p>
                    <a href="{{ route('frontend.portfolios.create') }}" class="inline-block mt-3 px-4 py-2 bg-orange-500 text-white font-semibold text-sm rounded-lg hover:bg-orange-400 transition">+ เพิ่มผลงาน</a>
                </div>
            @endforelse
        </div>

    @elseif($tab === 'bookings')
        <div class="space-y-4">
            <h3 class="font-bold text-white flex items-center gap-2">📦 การจองงาน</h3>
            @forelse($myWorkBookings ?? [] as $b)
                <div class="bg-gray-900 rounded-xl p-4">
                    <div class="flex justify-between items-start">
                        <p class="font-semibold text-white text-sm">{{ $b['work']['title'] ?? '-' }}</p>
                        @php
                            $statusColor = match(true) {
                                str_contains($b['booking_status'] ?? '', 'confirm') => 'bg-green-500/20 text-green-400',
                                str_contains($b['booking_status'] ?? '', 'cancel')  => 'bg-red-500/20 text-red-400',
                                default => 'bg-gray-700 text-gray-400',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $statusColor }}">
                            {{ $b['booking_status'] }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">📅 {{ $b['booking_date'] ?? '-' }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">ยังไม่มีการจองงาน</p>
            @endforelse

            <h3 class="font-bold text-white flex items-center gap-2 mt-6">👷 การสมัครงาน</h3>
            @forelse($myRecruitBookings ?? [] as $b)
                <div class="bg-gray-900 rounded-xl p-4">
                    <div class="flex justify-between items-start">
                        <p class="font-semibold text-white text-sm">{{ $b['recruit']['title'] ?? '-' }}</p>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-700 text-gray-400">{{ $b['booking_status'] }}</span>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">📅 {{ $b['booking_date'] ?? '-' }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">ยังไม่มีการสมัครงาน</p>
            @endforelse
        </div>

    @elseif($tab === 'reviews')
        @forelse($myReviews ?? [] as $review)
            <div class="bg-gray-900 rounded-xl p-4 mb-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-yellow-400">{{ str_repeat('★', $review['rating'] ?? 0) }}{{ str_repeat('☆', 5 - ($review['rating'] ?? 0)) }}</span>
                    <span class="text-gray-500 text-xs">{{ \Illuminate\Support\Carbon::parse($review['created_at'])->diffForHumans() }}</span>
                </div>
                @if($review['title'] ?? null)
                    <p class="font-semibold text-white text-sm">{{ $review['title'] }}</p>
                @endif
                <p class="text-gray-300 text-sm">{{ $review['content'] }}</p>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-4xl mb-2">⭐</div>
                <p class="text-gray-500">ยังไม่มีรีวิว</p>
            </div>
        @endforelse
    @endif

    </div>

</div>
