<div class="max-w-4xl mx-auto px-4 py-6">

    @if(!$recruit)
        <p class="text-gray-400 text-center py-20">ไม่พบประกาศนี้</p>
    @else
    <div class="space-y-6">

        {{-- Image + header --}}
        <div class="rounded-2xl overflow-hidden aspect-video bg-gray-800">
            <img src="{{ $recruit->primary_image ?? 'https://picsum.photos/seed/r'.$recruit->id.'/800/450' }}"
                 class="w-full h-full object-cover" alt="{{ $recruit->title }}">
        </div>

        <div>
            <div class="flex items-center gap-2 flex-wrap text-sm mb-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $recruit->recruit_status === 'stand-by' ? 'bg-green-500/20 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                    {{ match($recruit->recruit_status) { 'stand-by'=>'🟢 รับสมัคร','busy'=>'🟡 ไม่ว่างตอนนี้','close'=>'🔴 ปิดรับสมัคร',default=>$recruit->recruit_status } }}
                </span>
                <span class="text-gray-400">📍 {{ $recruit->province?->name_th ?? '-' }}</span>
                <span class="text-gray-400">🚛 {{ $recruit->workType?->title ?? '-' }}</span>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ $recruit->title }}</h1>
            <p class="text-3xl font-black text-green-400 mt-1">฿{{ number_format($recruit->budget) }}<span class="text-sm font-normal text-gray-400">/เดือน</span></p>
        </div>

        {{-- Owner Bar --}}
        @if($isOwner)
        <div class="bg-gradient-to-r from-green-500/10 to-green-600/5 border border-green-500/30 rounded-2xl p-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <span class="text-green-400 text-sm font-bold">👑 คุณเป็นเจ้าของประกาศนี้</span>
                <div class="flex gap-2">
                    <a href="{{ route('frontend.recruits.edit', $recruit->id) }}"
                       class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-xl text-sm transition">
                        ✏️ แก้ไข
                    </a>
                    <a href="{{ route('frontend.recruits.bookings', $recruit->id) }}"
                       class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-xl text-sm transition ring-1 ring-gray-700">
                        📋 จัดการสมัคร ({{ $bookingsCount }})
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Description --}}
        <div class="bg-gray-900 rounded-xl p-4">
            <h2 class="font-bold text-white mb-2">รายละเอียดงาน</h2>
            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $recruit->description }}</p>
        </div>

        {{-- Employer --}}
        <div class="bg-gray-900 rounded-xl p-4 flex items-center gap-4">
            <img src="{{ $recruit->author?->profile_image ?? 'https://ui-avatars.com/api/?name='.urlencode($recruit->author?->name ?? 'U') }}"
                 class="w-12 h-12 rounded-full object-cover ring-2 ring-green-500/30" alt="">
            <div>
                <p class="font-semibold text-white">{{ $recruit->author?->name }}</p>
                <p class="text-gray-400 text-sm">ผู้ประกาศ</p>
            </div>
        </div>

        {{-- Apply Form --}}
        <div class="bg-gray-900 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white">📋 สมัครงานนี้</h2>
                @auth
                    <button wire:click="$toggle('showApplyForm')"
                            class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-full text-sm transition">
                        {{ $showApplyForm ? 'ยกเลิก' : 'สมัครเลย' }}
                    </button>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-full text-sm transition">
                        เข้าสู่ระบบเพื่อสมัคร
                    </a>
                @endauth
            </div>

            @if($applySuccess)
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm">
                    {{ $applySuccess }}
                </div>
            @endif

            @if($showApplyForm)
                <form wire:submit="submitApply" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">เบอร์โทรติดต่อ *</label>
                            <input wire:model="applyPhone" type="tel" placeholder="08x-xxx-xxxx"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-green-500">
                            @error('applyPhone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1">วันที่สามารถเริ่มงานได้ *</label>
                            <input wire:model="applyDate" type="date"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-green-500">
                            @error('applyDate')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <textarea wire:model="applyMessage" rows="3" placeholder="แนะนำตัวเองและประสบการณ์..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-green-500 resize-none"></textarea>
                    <button type="submit"
                            class="w-full py-2.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg transition">
                        ✅ ส่งใบสมัคร
                    </button>
                </form>
            @endif
        </div>

        {{-- Reviews --}}
        @livewire('frontend.review-section', ['entityType' => 'recruit', 'entityId' => $recruit->id], key('reviews-'.$recruit->id))

    </div>
    @endif

</div>
