<div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    @if(!$recruit)
        <p class="text-gray-400 text-center py-10 md:py-20">ไม่พบประกาศนี้</p>
    @else
    <div class="space-y-3 md:space-y-6">

        {{-- Image + header --}}
        <div class="rounded-2xl overflow-hidden aspect-video bg-gray-800">
            <img src="{{ image_url($recruit->primary_image, 'https://picsum.photos/seed/r'.$recruit->id.'/800/450') }}"
                 class="w-full h-full object-cover" alt="{{ $recruit->title }}">
        </div>

        <div>
            <div class="flex items-center gap-1.5 md:gap-2 flex-wrap text-xs md:text-sm mb-1.5 md:mb-2">
                <span class="px-2 py-0.5 rounded-full text-[10px] md:text-xs font-medium
                    {{ $recruit->recruit_status === 'stand-by' ? 'bg-green-500/20 text-green-400' : 'bg-gray-700 text-gray-400' }}">
                    {{ match($recruit->recruit_status) { 'stand-by'=>'🟢 รับสมัคร','busy'=>'🟡 ไม่ว่างตอนนี้','close'=>'🔴 ปิดรับสมัคร',default=>$recruit->recruit_status } }}
                </span>
                <span class="text-gray-400">📍 {{ $recruit->province?->name_th ?? '-' }}</span>
                <span class="text-gray-400">🚛 {{ $recruit->workType?->title ?? '-' }}</span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold text-white">{{ $recruit->title }}</h1>
            <p class="text-2xl md:text-3xl font-black text-green-400 mt-1">฿{{ number_format($recruit->budget) }}<span class="text-xs md:text-sm font-normal text-gray-400">/ครั้ง</span></p>
        </div>

        {{-- Owner Bar --}}
        @if($isOwner)
        <div class="bg-gradient-to-r from-green-500/10 to-green-600/5 border border-green-500/30 rounded-2xl p-3 md:p-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <span class="text-green-400 text-xs md:text-sm font-bold w-full md:w-auto">👑 คุณเป็นเจ้าของประกาศนี้</span>
                <div class="flex gap-1.5 md:gap-2 w-full md:w-auto">
                    <a href="{{ route('frontend.recruits.edit', $recruit->id) }}"
                       class="flex-1 md:flex-none px-3 py-1.5 md:px-4 md:py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg md:rounded-xl text-xs md:text-sm transition text-center">
                        ✏️ แก้ไข
                    </a>
                    <a href="{{ route('frontend.recruits.bookings', $recruit->id) }}"
                       class="flex-1 md:flex-none px-3 py-1.5 md:px-4 md:py-2 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-lg md:rounded-xl text-xs md:text-sm transition ring-1 ring-gray-700 text-center">
                        📋 จัดการสมัคร ({{ $bookingsCount }})
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Description --}}
        <div class="bg-gray-900 rounded-xl p-3.5 md:p-4">
            <h2 class="font-bold text-white mb-2 text-sm md:text-base">รายละเอียดงาน</h2>
            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap text-sm md:text-base">{{ $recruit->description }}</p>
        </div>

        {{-- Employer --}}
        <div class="bg-gray-900 rounded-xl p-3.5 md:p-4 flex items-center gap-3 md:gap-4">
            <x-avatar :src="$recruit->author?->profile_image" :name="$recruit->author?->name" size="w-10 h-10 md:w-12 md:h-12" :border="false" class="ring-2 ring-green-500/30" />
            <div>
                <p class="font-semibold text-white text-sm md:text-base">{{ $recruit->author?->name }}</p>
                <p class="text-gray-400 text-xs md:text-sm">ผู้ประกาศ</p>
            </div>
        </div>

        {{-- Apply Form --}}
        <div class="bg-gray-900 rounded-xl p-3.5 md:p-4">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <h2 class="font-bold text-white text-sm md:text-base">📋 สมัครงานนี้</h2>
                @auth
                    <button wire:click="$toggle('showApplyForm')"
                            class="px-3 py-1.5 md:px-4 md:py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-full text-xs md:text-sm transition">
                        {{ $showApplyForm ? 'ยกเลิก' : 'สมัครเลย' }}
                    </button>
                @else
                    <a href="{{ route('frontend.auth.login') }}"
                       class="px-3 py-1.5 md:px-4 md:py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-full text-xs md:text-sm transition">
                        เข้าสู่ระบบเพื่อสมัคร
                    </a>
                @endauth
            </div>

            @if($applySuccess)
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-3 py-2.5 md:px-4 md:py-3 text-xs md:text-sm mb-3">
                    {{ $applySuccess }}
                </div>
            @endif

            @if($showApplyForm)
                <form wire:submit="submitApply" class="space-y-2.5 md:space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 md:gap-3">
                        <div>
                            <label class="block text-gray-400 text-[10px] md:text-xs mb-1">เบอร์โทรติดต่อ *</label>
                            <input wire:model="applyPhone" type="tel" placeholder="08x-xxx-xxxx"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-xs md:text-sm focus:outline-none focus:border-green-500">
                            @error('applyPhone')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 text-[10px] md:text-xs mb-1">วันที่สามารถเริ่มงานได้ *</label>
                            <input wire:model="applyDate" type="date"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-xs md:text-sm focus:outline-none focus:border-green-500">
                            @error('applyDate')<p class="text-red-400 text-[10px] md:text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <textarea wire:model="applyMessage" rows="3" placeholder="แนะนำตัวเองและประสบการณ์..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-xs md:text-sm focus:outline-none focus:border-green-500 resize-none"></textarea>
                    <button type="submit"
                            class="w-full py-2 md:py-2.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg transition text-xs md:text-sm mt-1">
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
