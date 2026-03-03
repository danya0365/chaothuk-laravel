<div class="max-w-4xl mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold text-white mb-6">🕐 ประวัติการทำงาน</h1>

    {{-- Status Filter --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        @foreach([
            'all'       => 'ทั้งหมด',
            'active'    => '🟢 กำลังทำ',
            'paused'    => '⏸ หยุดชั่วคราว',
            'completed' => '✅ เสร็จสิ้น',
            'cancelled' => '❌ ยกเลิก',
        ] as $key => $label)
            <button wire:click="setStatusFilter('{{ $key }}')"
                    class="whitespace-nowrap px-3 py-1.5 rounded-full text-xs font-medium transition
                           {{ $statusFilter === $key ? 'bg-orange-500/20 text-orange-400 ring-1 ring-orange-500/50' : 'bg-gray-800/50 text-gray-500 hover:text-gray-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if(count($sessions) === 0)
        <div class="text-center py-16">
            <div class="text-5xl mb-3">🕐</div>
            <p class="text-gray-400 font-medium">ยังไม่มีประวัติการทำงาน{{ $statusFilter !== 'all' ? ' ในสถานะนี้' : '' }}</p>
        </div>
    @else
        <div class="space-y-3">
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
                   class="bg-gray-900 rounded-xl p-4 border-l-4 {{ $statusColor }} block hover:bg-gray-800/70 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-800 text-gray-400">
                                    {{ $session['sessionable_type'] === 'Work' ? '📦 งาน' : '👷 รับสมัคร' }} #{{ $session['sessionable_id'] }}
                                </span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
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
                            <div class="text-sm text-gray-300 mt-2">
                                <span class="text-gray-500">{{ $session['is_worker'] ? 'ลูกค้า:' : 'ผู้ให้บริการ:' }}</span>
                                <span class="font-medium text-white">{{ $session['is_worker'] ? $session['customer_name'] : $session['worker_name'] }}</span>
                            </div>
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                <span>🕐 {{ $session['started_at'] }}</span>
                                @if($session['ended_at'])
                                    <span>→ {{ $session['ended_at'] }}</span>
                                @endif
                                @if($session['duration'] > 0)
                                    <span>⏱ {{ floor($session['duration'] / 60) }}ชม. {{ $session['duration'] % 60 }}น.</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            @if($session['price_agreed'])
                                <div class="text-lg font-bold text-orange-400">฿{{ number_format($session['price_agreed']) }}</div>
                            @endif
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-xs {{ $session['worker_confirm'] === 'confirmed' ? 'text-green-400' : 'text-gray-600' }}">👷{{ $session['worker_confirm'] === 'confirmed' ? '✓' : '…' }}</span>
                                <span class="text-xs {{ $session['customer_confirm'] === 'confirmed' ? 'text-green-400' : 'text-gray-600' }}">👤{{ $session['customer_confirm'] === 'confirmed' ? '✓' : '…' }}</span>
                            </div>
                            <div class="mt-2 text-xs text-orange-400 font-medium">ดูรายละเอียด →</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
