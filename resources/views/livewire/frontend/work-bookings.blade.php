<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('frontend.works.show', $id) }}"
               class="text-gray-400 hover:text-white transition">← กลับ</a>
            <div>
                <h1 class="text-xl font-bold text-white">📋 รายการจอง</h1>
                <p class="text-gray-500 text-sm">{{ $work->title }}</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-5 gap-2 mb-5">
        @php
            $statItems = [
                ['label' => 'ทั้งหมด', 'value' => $stats['total'], 'color' => 'text-white', 'bg' => 'bg-gray-800', 'filter' => ''],
                ['label' => 'รอยืนยัน', 'value' => $stats['waiting'], 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-500/10', 'filter' => 'waiting-to-confirm'],
                ['label' => 'ยืนยันแล้ว', 'value' => $stats['confirm'], 'color' => 'text-green-400', 'bg' => 'bg-green-500/10', 'filter' => 'confirm'],
                ['label' => 'ปิดแล้ว', 'value' => $stats['close'], 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/10', 'filter' => 'close'],
                ['label' => 'ยกเลิก', 'value' => $stats['cancel'], 'color' => 'text-red-400', 'bg' => 'bg-red-500/10', 'filter' => 'cancel'],
            ];
        @endphp
        @foreach($statItems as $s)
            <button wire:click="$set('filterStatus', '{{ $s['filter'] }}')"
                    class="rounded-xl p-3 text-center transition {{ $filterStatus === $s['filter'] ? 'ring-2 ring-orange-500' : '' }} {{ $s['bg'] }}">
                <div class="text-xl font-black {{ $s['color'] }}">{{ $s['value'] }}</div>
                <div class="text-gray-500 text-[10px]">{{ $s['label'] }}</div>
            </button>
        @endforeach
    </div>

    {{-- Bookings List --}}
    <div class="space-y-3">
        @forelse($bookings as $booking)
            <div class="bg-gray-900 rounded-xl p-4 {{ $booking->booking_status === 'waiting-to-confirm' ? 'ring-1 ring-orange-500/30' : '' }}">
                <div class="flex items-start gap-3">
                    <img src="{{ $booking->author?->getAvatar(48) ?? '' }}"
                         class="w-11 h-11 rounded-full object-cover flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        {{-- Name + Status --}}
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-white font-semibold">{{ $booking->author?->name ?? 'ผู้ใช้' }}</span>
                            @php
                                $sc = match($booking->booking_status) {
                                    'waiting-to-confirm' => ['bg-yellow-500/15 text-yellow-400 ring-yellow-500/30', '⏳ รอยืนยัน'],
                                    'confirm'            => ['bg-green-500/15 text-green-400 ring-green-500/30', '✅ ยืนยันแล้ว'],
                                    'close'              => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', '🔒 ปิดแล้ว'],
                                    'cancel'             => ['bg-red-500/15 text-red-400 ring-red-500/30', '❌ ยกเลิก'],
                                    default              => ['bg-gray-500/15 text-gray-400 ring-gray-500/30', $booking->booking_status],
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full ring-1 {{ $sc[0] }}">
                                {{ $sc[1] }}
                            </span>
                        </div>

                        {{-- Details --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-1 text-sm text-gray-400">
                            <span>📅 {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') : '-' }}</span>
                            <span>📞 {{ $booking->mobile_phone ?? '-' }}</span>
                            <span>🕐 {{ $booking->created_at?->diffForHumans() }}</span>
                        </div>

                        @if($booking->customer_message)
                            <div class="mt-2 bg-gray-800 rounded-lg px-3 py-2">
                                <p class="text-gray-300 text-sm">💬 {{ $booking->customer_message }}</p>
                            </div>
                        @endif

                        {{-- Confirm statuses --}}
                        <div class="flex items-center gap-3 mt-2 text-[11px]">
                            <span class="{{ $booking->customer_confirm_status === 'confirm' ? 'text-green-400' : 'text-gray-600' }}">
                                👤 ลูกค้า: {{ match($booking->customer_confirm_status) {
                                    'confirm' => '✅ ยืนยัน', 'rejected' => '❌ ปฏิเสธ', default => '⏳ รอ'
                                } }}
                            </span>
                            <span class="{{ $booking->worker_confirm_status === 'confirm' ? 'text-green-400' : 'text-gray-600' }}">
                                🔧 ผู้รับงาน: {{ match($booking->worker_confirm_status) {
                                    'confirm' => '✅ ยืนยัน', 'rejected' => '❌ ปฏิเสธ', default => '⏳ รอ'
                                } }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        @if($booking->booking_status === 'waiting-to-confirm')
                            <div class="flex gap-2 mt-3">
                                <button wire:click="confirmBooking({{ $booking->id }})"
                                        wire:confirm="ยืนยันการจองนี้?"
                                        class="px-5 py-2 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded-lg transition">
                                    ✅ ยืนยัน
                                </button>
                                <button wire:click="cancelBooking({{ $booking->id }})"
                                        wire:confirm="ต้องการยกเลิกการจองนี้?"
                                        class="px-5 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-xs font-bold rounded-lg transition ring-1 ring-red-500/30">
                                    ❌ ยกเลิก
                                </button>
                            </div>
                        @elseif($booking->booking_status === 'confirm')
                            <div class="flex gap-2 mt-3">
                                <button wire:click="closeBooking({{ $booking->id }})"
                                        wire:confirm="ปิดงานนี้? (งานเสร็จสิ้น)"
                                        class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg transition">
                                    🔒 ปิดงาน (เสร็จสิ้น)
                                </button>
                                <button wire:click="cancelBooking({{ $booking->id }})"
                                        wire:confirm="ต้องการยกเลิกการจองนี้?"
                                        class="px-5 py-2 bg-red-600/20 hover:bg-red-600/40 text-red-400 text-xs font-bold rounded-lg transition ring-1 ring-red-500/30">
                                    ❌ ยกเลิก
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-gray-500">{{ $filterStatus ? 'ไม่พบรายการจองในสถานะนี้' : 'ยังไม่มีการจอง' }}</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>

</div>
