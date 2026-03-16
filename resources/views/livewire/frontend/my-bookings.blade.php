<div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    <h1 class="text-xl md:text-2xl font-bold text-white mb-4 md:mb-6">📋 การจองของฉัน</h1>

    {{-- Flash --}}
    @if($flashMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm mb-3 md:mb-4">
            {{ $flashMessage }}
        </div>
    @endif

    {{-- Tabs: Work / Recruit --}}
    <div class="flex gap-1.5 md:gap-2 mb-3 md:mb-4">
        @foreach(['work' => '📦 งาน', 'recruit' => '👷 รับสมัคร'] as $key => $label)
            <button wire:click="switchTab('{{ $key }}')"
                    class="px-3 py-1.5 md:px-4 md:py-2 rounded-full text-xs md:text-sm font-semibold transition
                           {{ $tab === $key ? 'bg-orange-500 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Status Filter --}}
    <div class="flex gap-1.5 md:gap-2 mb-4 md:mb-6 overflow-x-auto pb-1">
        @foreach([
            'all' => 'ทั้งหมด',
            'waiting-to-confirm' => '⏳ รอยืนยัน',
            'confirm' => '✅ ยืนยัน',
            'close' => '🔒 เสร็จสิ้น',
            'cancel' => '❌ ยกเลิก',
        ] as $key => $label)
            <button wire:click="setStatusFilter('{{ $key }}')"
                    class="whitespace-nowrap px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-[10px] md:text-xs font-medium transition
                           {{ $statusFilter === $key ? 'bg-orange-500/20 text-orange-400 ring-1 ring-orange-500/50' : 'bg-gray-800/50 text-gray-500 hover:text-gray-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Bookings List --}}
    @php
        $bookings = $tab === 'work' ? ($workBookings ?? []) : ($recruitBookings ?? []);
    @endphp

    @if(count($bookings) === 0)
        <div class="text-center py-10 md:py-16">
            <div class="text-4xl md:text-5xl mb-2 md:mb-3">📭</div>
            <p class="text-gray-400 font-medium text-sm md:text-base">ยังไม่มีการจอง{{ $statusFilter !== 'all' ? ' ในสถานะนี้' : '' }}</p>
        </div>
    @else
        <div class="space-y-2 md:space-y-3">
            @foreach($bookings as $booking)
                @php
                    $item = $tab === 'work' ? ($booking['work'] ?? null) : ($booking['recruit'] ?? null);
                    $statusColor = match($booking['booking_status'] ?? '') {
                        'confirm'            => 'border-green-500 bg-green-500/5',
                        'waiting-to-confirm' => 'border-yellow-500 bg-yellow-500/5',
                        'cancel'             => 'border-red-500 bg-red-500/5',
                        'close'              => 'border-gray-600 bg-gray-800/50',
                        default              => 'border-gray-700',
                    };
                    $statusLabel = match($booking['booking_status'] ?? '') {
                        'confirm'            => '✅ ยืนยันแล้ว',
                        'waiting-to-confirm' => '⏳ รอยืนยัน',
                        'cancel'             => '❌ ยกเลิก',
                        'close'              => '🔒 เสร็จสิ้น',
                        default              => $booking['booking_status'] ?? '-',
                    };
                @endphp
                <div class="bg-gray-900 rounded-xl p-3 md:p-4 border-l-4 {{ $statusColor }}">
                    <div class="flex items-start gap-3 md:gap-4">
                        {{-- Image --}}
                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-lg overflow-hidden bg-gray-800 flex-shrink-0">
                            <img src="{{ $item['primary_image'] ?? 'https://picsum.photos/seed/'.($item['id'] ?? 0).'/200/200' }}"
                                 class="w-full h-full object-cover" alt="">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-white text-xs md:text-sm truncate">{{ $item['title'] ?? '-' }}</h3>
                            <div class="flex items-center gap-1.5 md:gap-2 mt-0.5 md:mt-1 text-[10px] md:text-xs text-gray-400">
                                <span>📅 {{ $booking['booking_date'] ?? '-' }}</span>
                                <span>📞 {{ $booking['mobile_phone'] ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1 md:mt-1.5">
                                <span class="text-[10px] md:text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ match($booking['booking_status'] ?? '') {
                                        'confirm'            => 'bg-green-500/20 text-green-400',
                                        'waiting-to-confirm' => 'bg-yellow-500/20 text-yellow-400',
                                        'cancel'             => 'bg-red-500/20 text-red-400',
                                        'close'              => 'bg-gray-600/20 text-gray-400',
                                        default              => 'bg-gray-700 text-gray-400',
                                    } }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>
                        {{-- Cancel button --}}
                        @if(($booking['booking_status'] ?? '') === 'waiting-to-confirm')
                            <button wire:click="cancelBooking('{{ $tab }}', {{ $booking['id'] }})"
                                    wire:confirm="ยืนยันยกเลิกการจอง?"
                                    class="px-2.5 py-1 md:px-3 md:py-1.5 text-[10px] md:text-xs bg-red-500/10 text-red-400 border border-red-500/30 rounded-lg hover:bg-red-500/20 transition flex-shrink-0">
                                ยกเลิก
                            </button>
                        @endif
                    </div>
                    @if($booking['customer_message'] ?? null)
                        <p class="text-gray-500 text-[10px] md:text-xs mt-1.5 md:mt-2 italic pl-14 md:pl-20">"{{ $booking['customer_message'] }}"</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</div>
