<div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4 md:mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-white">📅 ปฏิทินการจอง</h1>
    </div>

    {{-- Month Navigation --}}
    <div class="bg-gray-900 rounded-2xl p-3 md:p-4 mb-4 md:mb-6">
        <div class="flex items-center justify-between mb-3 md:mb-4">
            <button wire:click="prevMonth"
                    class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                ◀
            </button>
            <h2 class="text-lg font-bold text-white">{{ $monthLabel }}</h2>
            <button wire:click="nextMonth"
                    class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                ▶
            </button>
        </div>

        {{-- Day of week headers --}}
        <div class="grid grid-cols-7 gap-0.5 md:gap-1 mb-1 md:mb-2">
            @foreach(['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'] as $dow)
                <div class="text-center text-[10px] md:text-xs text-gray-500 font-medium py-1">{{ $dow }}</div>
            @endforeach
        </div>

        {{-- Calendar grid --}}
        <div class="grid grid-cols-7 gap-0.5 md:gap-1">
            @foreach($days as $cell)
                @if($cell['day'] === null)
                    <div class="aspect-square"></div>
                @else
                    <button wire:click="selectDate('{{ $cell['date'] }}')"
                            class="aspect-square rounded-xl flex flex-col items-center justify-center text-sm font-medium transition relative
                                {{ $selectedDate === $cell['date']
                                    ? 'bg-orange-500 text-white ring-2 ring-orange-400'
                                    : ($cell['today']
                                        ? 'bg-orange-500/20 text-orange-400 hover:bg-orange-500/30'
                                        : ($cell['booked']
                                            ? 'bg-blue-500/15 text-blue-400 hover:bg-blue-500/25'
                                            : 'text-gray-300 hover:bg-gray-800')) }}">
                        <span>{{ $cell['day'] }}</span>
                        @if($cell['booked'])
                            <span class="absolute bottom-1 w-1.5 h-1.5 rounded-full
                                {{ $selectedDate === $cell['date'] ? 'bg-white' : 'bg-blue-400' }}"></span>
                        @endif
                    </button>
                @endif
            @endforeach
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-2 md:gap-4 mt-3 md:mt-4 text-[10px] md:text-xs text-gray-500 flex-wrap">
            <div class="flex items-center gap-1 md:gap-1.5">
                <span class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-orange-500/20 border border-orange-500/50"></span>
                วันนี้
            </div>
            <div class="flex items-center gap-1 md:gap-1.5">
                <span class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-blue-500/15 border border-blue-500/50"></span>
                มีการจอง
            </div>
            <div class="flex items-center gap-1 md:gap-1.5">
                <span class="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-gray-800 border border-gray-700"></span>
                ว่าง
            </div>
        </div>
    </div>

    {{-- Selected Day Details --}}
    @if($selectedDate)
        <div class="bg-gray-900 rounded-2xl p-3 md:p-4">
            <h3 class="font-bold text-white text-sm md:text-base mb-2 md:mb-3">
                📋 การจองวันที่ {{ \Illuminate\Support\Carbon::parse($selectedDate)->locale('th')->translatedFormat('j F Y') }}
            </h3>

            @if(empty($selectedDayBookings))
                <div class="text-center py-6 md:py-8">
                    <div class="text-3xl md:text-4xl mb-2">✅</div>
                    <p class="text-green-400 text-sm md:text-base font-semibold">ว่าง — ไม่มีการจองในวันนี้</p>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5 md:mt-1">คุณสามารถรับงานใหม่ได้</p>
                </div>
            @else
                <div class="space-y-2 md:space-y-3">
                    @foreach($selectedDayBookings as $booking)
                        <div class="bg-gray-800 rounded-xl p-3 md:p-4 border-l-4
                            {{ $booking['status'] === 'confirm' ? 'border-green-500' :
                               ($booking['status'] === 'waiting-to-confirm' ? 'border-yellow-500' :
                               ($booking['status'] === 'cancel' ? 'border-red-500' : 'border-gray-600')) }}">
                            <div class="flex items-start justify-between gap-2 md:gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-1.5 md:gap-2 mb-1">
                                        <span class="text-[10px] md:text-xs px-2 py-0.5 rounded-full font-medium
                                            {{ $booking['type'] === 'work'
                                                ? 'bg-orange-500/20 text-orange-400'
                                                : 'bg-purple-500/20 text-purple-400' }}">
                                            {{ $booking['type'] === 'work' ? '📦 งาน' : '👷 รับสมัคร' }}
                                        </span>
                                        <span class="text-[10px] md:text-xs px-2 py-0.5 rounded-full font-medium
                                            {{ $booking['status'] === 'confirm' ? 'bg-green-500/20 text-green-400' :
                                               ($booking['status'] === 'waiting-to-confirm' ? 'bg-yellow-500/20 text-yellow-400' :
                                               ($booking['status'] === 'cancel' ? 'bg-red-500/20 text-red-400' : 'bg-gray-600/20 text-gray-400')) }}">
                                            {{ match($booking['status']) {
                                                'confirm' => '✅ ยืนยัน',
                                                'waiting-to-confirm' => '⏳ รอยืนยัน',
                                                'cancel' => '❌ ยกเลิก',
                                                'close' => '🔒 ปิด',
                                                default => $booking['status'],
                                            } }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-white text-sm mt-1">{{ $booking['title'] }}</h4>
                                    <p class="text-gray-400 text-xs md:text-sm mt-0.5 md:mt-1">
                                        👤 {{ $booking['author'] }}
                                        @if($booking['phone'])
                                            · 📞 {{ $booking['phone'] }}
                                        @endif
                                    </p>
                                    @if($booking['message'])
                                        <p class="text-gray-500 text-sm mt-1 italic">"{{ $booking['message'] }}"</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

</div>
