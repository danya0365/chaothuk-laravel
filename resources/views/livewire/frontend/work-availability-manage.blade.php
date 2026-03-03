<div class="max-w-2xl mx-auto px-4 py-6">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('frontend.works.show', $id) }}" class="text-gray-400 hover:text-white transition">←</a>
        <h1 class="text-2xl font-bold text-white">📅 ตารางว่าง — {{ $work->title }}</h1>
    </div>

    @if($flashMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-4 py-3 text-sm mb-4">
            {{ $flashMessage }}
        </div>
    @endif

    {{-- Weekly Schedule --}}
    <div class="bg-gray-900 rounded-xl p-4 mb-6">
        <h2 class="font-semibold text-white mb-4">📋 ตารางประจำสัปดาห์</h2>
        <div class="space-y-3">
            @foreach($schedule as $s)
                <div class="flex items-center gap-3 {{ $s['is_active'] ? '' : 'opacity-50' }}">
                    {{-- Toggle --}}
                    <button wire:click="toggleDay({{ $s['day'] }})"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition
                                   {{ $s['is_active'] ? 'bg-green-500 text-white' : 'bg-gray-700 text-gray-500 hover:bg-gray-600' }}">
                        {{ $s['is_active'] ? '✓' : '✗' }}
                    </button>

                    {{-- Day name --}}
                    <span class="w-20 text-sm font-medium {{ $s['is_active'] ? 'text-white' : 'text-gray-500' }}">
                        {{ $s['name'] }}
                    </span>

                    @if($s['is_active'])
                        {{-- Start time --}}
                        <input type="time" value="{{ $s['start_time'] }}"
                               wire:change="updateTime({{ $s['day'] }}, 'start_time', $event.target.value)"
                               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-white text-sm focus:outline-none focus:border-orange-500">
                        <span class="text-gray-500 text-sm">—</span>
                        {{-- End time --}}
                        <input type="time" value="{{ $s['end_time'] }}"
                               wire:change="updateTime({{ $s['day'] }}, 'end_time', $event.target.value)"
                               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-white text-sm focus:outline-none focus:border-orange-500">
                    @else
                        <span class="text-gray-600 text-sm">หยุด</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Blocked Dates --}}
    <div class="bg-gray-900 rounded-xl p-4">
        <h2 class="font-semibold text-white mb-4">🚫 วันหยุดพิเศษ</h2>

        {{-- Add form --}}
        <div class="flex items-end gap-2 mb-4">
            <div class="flex-1">
                <label class="block text-xs text-gray-400 mb-1">วันที่</label>
                <input type="date" wire:model="newBlockedDate" min="{{ now()->format('Y-m-d') }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
            </div>
            <div class="flex-1">
                <label class="block text-xs text-gray-400 mb-1">เหตุผล (ไม่บังคับ)</label>
                <input type="text" wire:model="newBlockedReason" placeholder="เช่น ลาพักร้อน"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-orange-500">
            </div>
            <button wire:click="addBlockedDate"
                    class="px-4 py-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-lg text-sm transition flex-shrink-0">
                + เพิ่ม
            </button>
        </div>
        @error('newBlockedDate') <p class="text-red-400 text-xs mb-2">{{ $message }}</p> @enderror

        {{-- List --}}
        @if(count($blockedDates) === 0)
            <p class="text-gray-500 text-sm text-center py-4">ยังไม่มีวันหยุดพิเศษ</p>
        @else
            <div class="space-y-2">
                @foreach($blockedDates as $bd)
                    <div class="flex items-center justify-between bg-gray-800 rounded-lg px-3 py-2">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-white font-medium">
                                {{ \Carbon\Carbon::parse($bd['blocked_date'])->format('d/m/Y') }}
                            </span>
                            @if($bd['reason'])
                                <span class="text-xs text-gray-500">— {{ $bd['reason'] }}</span>
                            @endif
                        </div>
                        <button wire:click="removeBlockedDate({{ $bd['id'] }})"
                                class="text-red-400 hover:text-red-300 text-xs transition">✕ ลบ</button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
