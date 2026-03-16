<div class="max-w-2xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    <div class="flex items-center gap-2 md:gap-3 mb-4 md:mb-6">
        <a href="{{ route('frontend.works.show', $id) }}" class="text-xs md:text-sm text-gray-400 hover:text-white transition">←</a>
        <h1 class="text-lg md:text-2xl font-bold text-white">📅 ตารางว่าง — {{ $work->title }}</h1>
    </div>

    @if($flashMessage)
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-lg px-3 md:px-4 py-2 md:py-3 text-xs md:text-sm mb-3 md:mb-4">
            {{ $flashMessage }}
        </div>
    @endif

    {{-- Weekly Schedule --}}
    <div class="bg-gray-900 rounded-xl p-3 md:p-4 mb-4 md:mb-6">
        <h2 class="font-semibold text-white text-sm md:text-base mb-3 md:mb-4">📋 ตารางประจำสัปดาห์</h2>
        <div class="space-y-2.5 md:space-y-3">
            @foreach($schedule as $s)
                <div class="flex items-center gap-2 md:gap-3 flex-wrap sm:flex-nowrap {{ $s['is_active'] ? '' : 'opacity-50' }}">
                    <div class="flex items-center gap-2 md:gap-3 shrink-0">
                        {{-- Toggle --}}
                        <button wire:click="toggleDay({{ $s['day'] }})"
                                class="w-7 h-7 md:w-8 md:h-8 shrink-0 rounded-full flex items-center justify-center text-xs md:text-sm font-bold transition
                                       {{ $s['is_active'] ? 'bg-green-500 text-white' : 'bg-gray-700 text-gray-500 hover:bg-gray-600' }}">
                            {{ $s['is_active'] ? '✓' : '✗' }}
                        </button>

                    {{-- Day name --}}
                    <span class="w-16 sm:w-20 text-xs md:text-sm font-medium {{ $s['is_active'] ? 'text-white' : 'text-gray-500' }}">
                        {{ $s['name'] }}
                    </span>
                    </div>

                    @if($s['is_active'])
                        <div class="flex items-center gap-1 md:gap-2 flex-1 w-full sm:w-auto">
                            {{-- Start time --}}
                            <input type="time" value="{{ $s['start_time'] }}"
                                   wire:change="updateTime({{ $s['day'] }}, 'start_time', $event.target.value)"
                                   class="flex-1 min-w-[3rem] sm:min-w-0 bg-gray-800 border border-gray-700 rounded-lg px-2 text-[10px] md:px-3 md:py-1.5 md:text-sm text-white focus:outline-none focus:border-orange-500 !leading-loose h-8 md:h-9">
                            <span class="text-gray-500 text-[10px] md:text-xs shrink-0">—</span>
                            {{-- End time --}}
                            <input type="time" value="{{ $s['end_time'] }}"
                                   wire:change="updateTime({{ $s['day'] }}, 'end_time', $event.target.value)"
                                   class="flex-1 min-w-[3rem] sm:min-w-0 bg-gray-800 border border-gray-700 rounded-lg px-2 text-[10px] md:px-3 md:py-1.5 md:text-sm text-white focus:outline-none focus:border-orange-500 !leading-loose h-8 md:h-9">
                        </div>
                    @else
                        <span class="text-gray-600 text-xs md:text-sm px-2">หยุด</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Blocked Dates --}}
    <div class="bg-gray-900 rounded-xl p-3 md:p-4">
        <h2 class="font-semibold text-white mb-3 md:mb-4 text-sm md:text-base">🚫 วันหยุดพิเศษ</h2>

        {{-- Add form --}}
        <div class="flex flex-col sm:flex-row sm:items-end gap-2 mb-3 md:mb-4">
            <div class="flex-1">
                <label class="block text-[10px] md:text-xs text-gray-400 mb-1">วันที่</label>
                <input type="date" wire:model="newBlockedDate" min="{{ now()->format('Y-m-d') }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500 mt-1">
            </div>
            <div class="flex-1">
                <label class="block text-[10px] md:text-xs text-gray-400 mb-1">เหตุผล (ไม่บังคับ)</label>
                <input type="text" wire:model="newBlockedReason" placeholder="เช่น ลาพักร้อน"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5 text-white text-xs md:text-sm focus:outline-none focus:border-orange-500">
            </div>
            <button wire:click="addBlockedDate"
                    class="w-full sm:w-auto mt-2 sm:mt-0 px-4 py-2 md:py-1.5 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-lg text-xs md:text-sm transition flex-shrink-0">
                + เพิ่ม
            </button>
        </div>
        @error('newBlockedDate') <p class="text-red-400 text-[10px] md:text-xs mb-2">{{ $message }}</p> @enderror

        {{-- List --}}
        @if(count($blockedDates) === 0)
            <p class="text-gray-500 text-xs md:text-sm text-center py-3 md:py-4">ยังไม่มีวันหยุดพิเศษ</p>
        @else
            <div class="space-y-1.5 md:space-y-2">
                @foreach($blockedDates as $bd)
                    <div class="flex items-center justify-between bg-gray-800 rounded-lg px-2.5 md:px-3 py-1.5 md:py-2">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                            <span class="text-xs md:text-sm text-white font-medium">
                                {{ \Carbon\Carbon::parse($bd['blocked_date'])->format('d/m/Y') }}
                            </span>
                            @if($bd['reason'])
                                <span class="text-[10px] md:text-xs text-gray-500 sm:before:content-['—\_']">{{ $bd['reason'] }}</span>
                            @endif
                        </div>
                        <button wire:click="removeBlockedDate({{ $bd['id'] }})"
                                class="text-red-400 hover:text-red-300 text-[10px] md:text-xs transition px-2 py-1">✕ ลบ</button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
