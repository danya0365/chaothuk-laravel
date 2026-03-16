<div class="max-w-2xl mx-auto px-3 sm:px-4 py-4 md:py-6">

    <div class="flex items-center justify-between mb-4 md:mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-white">🔔 แจ้งเตือน</h1>
        @if(collect($notifications)->where('is_read', false)->count() > 0)
            <button wire:click="markAllRead"
                    class="text-xs md:text-sm text-orange-400 hover:underline">
                อ่านทั้งหมด
            </button>
        @endif
    </div>

    @forelse($notifications ?? [] as $n)
        <div wire:click="markRead({{ $n['id'] }})"
             class="cursor-pointer bg-gray-900 rounded-xl p-3 md:p-4 mb-2 md:mb-3 flex gap-2 md:gap-3 transition hover:bg-gray-800
                    {{ $n['is_read'] ? 'opacity-60' : 'border border-orange-500/20' }}">
            <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 {{ $n['is_read'] ? 'bg-gray-600' : 'bg-orange-400' }}"></div>
            <div class="flex-1">
                <p class="font-semibold text-white text-xs md:text-sm">{{ $n['title'] ?? 'แจ้งเตือน' }}</p>
                @if($n['details'] ?? null)
                    @php $details = is_string($n['details']) ? json_decode($n['details'], true) : $n['details']; @endphp
                    <p class="text-gray-400 text-[10px] md:text-xs mt-0.5">{{ $details['message'] ?? '' }}</p>
                @endif
                <p class="text-gray-600 text-[9px] md:text-xs mt-1">
                    {{ \Illuminate\Support\Carbon::parse($n['created_at'])->diffForHumans() }}
                    {{ $n['is_read'] ? '· อ่านแล้ว' : '' }}
                </p>
            </div>
        </div>
    @empty
        <div class="text-center py-10 md:py-20">
            <div class="text-4xl md:text-5xl mb-3 md:mb-4">🔔</div>
            <p class="text-gray-500 text-sm md:text-base">ยังไม่มีการแจ้งเตือน</p>
        </div>
    @endforelse

</div>
