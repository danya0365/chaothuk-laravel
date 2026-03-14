<div class="max-w-2xl mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">🔔 แจ้งเตือน</h1>
        @if(collect($notifications)->where('is_read', false)->count() > 0)
            <button wire:click="markAllRead"
                    class="text-sm text-orange-400 hover:underline">
                อ่านทั้งหมด
            </button>
        @endif
    </div>

    @forelse($notifications ?? [] as $n)
        <div wire:click="markRead({{ $n['id'] }})"
             class="cursor-pointer bg-gray-900 rounded-xl p-4 mb-3 flex gap-3 transition hover:bg-gray-800
                    {{ $n['is_read'] ? 'opacity-60' : 'border border-orange-500/20' }}">
            <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 {{ $n['is_read'] ? 'bg-gray-600' : 'bg-orange-400' }}"></div>
            <div class="flex-1">
                <p class="font-semibold text-white text-sm">{{ $n['title'] ?? 'แจ้งเตือน' }}</p>
                @if($n['details'] ?? null)
                    @php $details = is_string($n['details']) ? json_decode($n['details'], true) : $n['details']; @endphp
                    <p class="text-gray-400 text-xs mt-0.5">{{ $details['message'] ?? '' }}</p>
                @endif
                <p class="text-gray-600 text-xs mt-1">
                    {{ \Illuminate\Support\Carbon::parse($n['created_at'])->diffForHumans() }}
                    {{ $n['is_read'] ? '· อ่านแล้ว' : '' }}
                </p>
            </div>
        </div>
    @empty
        <div class="text-center py-20">
            <div class="text-5xl mb-4">🔔</div>
            <p class="text-gray-500">ยังไม่มีการแจ้งเตือน</p>
        </div>
    @endforelse

</div>
