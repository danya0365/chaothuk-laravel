<div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    <h1 class="text-xl md:text-2xl font-bold text-white mb-4 md:mb-6">🕐 ประวัติการทำงาน</h1>

    {{-- Status Filter --}}
    <div class="flex gap-1.5 md:gap-2 mb-4 md:mb-6 overflow-x-auto pb-1">
        @foreach([
            'all'       => 'ทั้งหมด',
            'active'    => '🟢 กำลังทำ',
            'paused'    => '⏸ หยุดชั่วคราว',
            'completed' => '✅ เสร็จสิ้น',
            'cancelled' => '❌ ยกเลิก',
        ] as $key => $label)
            <button wire:click="setStatusFilter('{{ $key }}')"
                    class="whitespace-nowrap px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-[10px] md:text-xs font-medium transition
                           {{ $statusFilter === $key ? 'bg-orange-500/20 text-orange-400 ring-1 ring-orange-500/50' : 'bg-gray-800/50 text-gray-500 hover:text-gray-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if(count($sessions) === 0)
        <div class="text-center py-10 md:py-16">
            <div class="text-4xl md:text-5xl mb-2 md:mb-3">🕐</div>
            <p class="text-gray-400 font-medium text-sm md:text-base">ยังไม่มีประวัติการทำงาน{{ $statusFilter !== 'all' ? ' ในสถานะนี้' : '' }}</p>
        </div>
    @else
        <div class="space-y-2 md:space-y-3">
            @foreach($sessions as $session)
                @php
                    $statusColor = match($session['status']) {
                        'active'    => 'border-green-500 bg-green-500/5',
                        'paused'    => 'border-yellow-500 bg-yellow-500/5',
                        'completed' => 'border-blue-500 bg-blue-500/5',
                        'cancelled' => 'border-red-500 bg-red-500/5',
                        default     => 'border-gray-700',
                    };
                    $statusLabel = match($session['status']) {
                        'active'    => '🟢 กำลังทำ',
                        'paused'    => '⏸ หยุดชั่วคราว',
                        'completed' => '✅ เสร็จสิ้น',
                        'cancelled' => '❌ ยกเลิก',
                        default     => $session['status'],
                    };
                @endphp
                <a href="{{ route('frontend.sessions.show', $session['id']) }}"
                   class="bg-gray-900 rounded-xl p-3 md:p-4 border-l-4 {{ $statusColor }} block hover:bg-gray-800/70 transition">
                    <div class="flex flex-col sm:flex-row items-start justify-between gap-3 sm:gap-0">
                        <div>
                            <div class="flex items-center gap-1.5 md:gap-2 mb-1">
                                <span class="text-[10px] md:text-xs px-2 py-0.5 rounded-full bg-gray-800 text-gray-400">
                                    {{ $session['sessionable_type'] === 'Work' ? '📦 งาน' : '👷 รับสมัคร' }} #{{ $session['sessionable_id'] }}
                                </span>
                                <span class="text-[10px] md:text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ match($session['status']) {
                                        'active'    => 'bg-green-500/20 text-green-400',
                                        'paused'    => 'bg-yellow-500/20 text-yellow-400',
                                        'completed' => 'bg-blue-500/20 text-blue-400',
                                        'cancelled' => 'bg-red-500/20 text-red-400',
                                        default     => 'bg-gray-700 text-gray-400',
                                    } }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="text-xs md:text-sm text-gray-300 mt-1.5 md:mt-2">
                                <span class="text-gray-500">{{ $session['is_worker'] ? 'ลูกค้า:' : 'ผู้ให้บริการ:' }}</span>
                                <span class="font-medium text-white">{{ $session['is_worker'] ? $session['customer_name'] : $session['worker_name'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 md:gap-4 mt-1.5 md:mt-2 text-[10px] md:text-xs text-gray-500 flex-wrap">
                                <span>🕐 {{ $session['started_at'] }}</span>
                                @if($session['ended_at'])
                                    <span>→ {{ $session['ended_at'] }}</span>
                                @endif
                                @if($session['duration'] > 0)
                                    <span>⏱ {{ floor($session['duration'] / 60) }}ชม. {{ $session['duration'] % 60 }}น.</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-left sm:text-right flex-shrink-0 w-full sm:w-auto bg-gray-800/50 sm:bg-transparent p-2 sm:p-0 rounded-lg sm:rounded-none flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-start">
                            <div class="flex flex-row sm:flex-col items-center sm:items-end gap-2 sm:gap-0">
                                @if($session['price_agreed'])
                                    <div class="text-sm md:text-lg font-bold text-orange-400">฿{{ number_format($session['price_agreed']) }}</div>
                                @endif
                                <div class="flex items-center gap-1 mt-0 sm:mt-1">
                                    <span class="text-[10px] md:text-xs {{ $session['worker_confirm'] === 'confirmed' ? 'text-green-400' : 'text-gray-600' }}">👷{{ $session['worker_confirm'] === 'confirmed' ? '✓' : '…' }}</span>
                                    <span class="text-[10px] md:text-xs {{ $session['customer_confirm'] === 'confirmed' ? 'text-green-400' : 'text-gray-600' }}">👤{{ $session['customer_confirm'] === 'confirmed' ? '✓' : '…' }}</span>
                                </div>
                            </div>
                            <div class="mt-0 sm:mt-2 text-[10px] md:text-xs text-orange-400 font-medium">ดูรายละเอียด →</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
