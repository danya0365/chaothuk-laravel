<div class="fixed bottom-20 md:bottom-6 right-4 md:right-6 z-[60] flex flex-col gap-3 pointer-events-none">
@if($shouldShow)
    
    {{-- Global Close Button --}}
    <div class="flex justify-end pointer-events-auto">
        <button wire:click="dismiss('{{ $currentHash }}')" class="bg-gray-800/80 hover:bg-gray-700 text-gray-400 hover:text-white rounded-full p-2 backdrop-blur-sm transition border border-gray-700 shadow-lg" title="ซ่อนการแจ้งเตือนชั่วคราว">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Active Sessions Toast --}}
    @foreach($activeSessions as $session)
        <div class="pointer-events-auto bg-gray-900 border border-blue-500/30 shadow-[0_8px_30px_rgb(59,130,246,0.15)] rounded-2xl p-4 w-72 md:w-80 animate-[slideInUp_0.4s_ease-out]">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                    <span class="text-blue-400 font-bold text-xs md:text-sm">กำลังทำงาน</span>
                </div>
                <div class="text-[10px] text-gray-500 bg-gray-800 px-2 py-0.5 rounded-full">
                    {{ $session->worker_id === $uid ? 'รับทำ' : 'ว่าจ้าง' }}
                </div>
            </div>
            <h4 class="text-white font-medium text-xs md:text-sm mb-3 line-clamp-2 leading-relaxed">
                {{ $session->sessionable?->title ?? 'งานบริการ' }}
            </h4>
            <a href="{{ route('frontend.sessions.show', $session->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-2 md:py-2.5 rounded-xl transition shadow-lg shadow-blue-500/20">
                เข้าห้องทำงาน 🚀
            </a>
        </div>
    @endforeach

    {{-- Pending Orders Toast --}}
    @if($pendingOrdersCount > 0)
        <div class="pointer-events-auto bg-gray-900 border border-orange-500/30 shadow-[0_8px_30px_rgb(249,115,22,0.15)] rounded-2xl p-4 w-72 md:w-80 animate-[slideInUp_0.4s_ease-out]">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </span>
                    <span class="text-orange-400 font-bold text-xs md:text-sm">ออเดอร์ใหม่!</span>
                </div>
            </div>
            <h4 class="text-white font-medium text-xs md:text-sm mb-3">
                คุณมี <span class="bg-orange-500/20 text-orange-400 px-1.5 py-0.5 rounded text-base font-black mx-1">{{ $pendingOrdersCount }}</span> คำขอใหม่ที่รอการยืนยัน
            </h4>
            <a href="{{ route('frontend.my-orders') }}" class="block w-full text-center bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-400 hover:to-amber-400 text-white text-xs font-bold py-2 md:py-2.5 rounded-xl transition shadow-lg shadow-orange-500/20">
                ตรวจสอบออเดอร์
            </a>
        </div>
    @endif

    {{-- Pending Recruits Toast --}}
    @if($pendingRecruitsCount > 0)
        <div class="pointer-events-auto bg-gray-900 border border-purple-500/30 shadow-[0_8px_30px_rgb(168,85,247,0.15)] rounded-2xl p-4 w-72 md:w-80 animate-[slideInUp_0.4s_ease-out]">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                    </span>
                    <span class="text-purple-400 font-bold text-xs md:text-sm">ผู้สมัครใหม่!</span>
                </div>
            </div>
            <h4 class="text-white font-medium text-xs md:text-sm mb-3">
                คุณมี <span class="bg-purple-500/20 text-purple-400 px-1.5 py-0.5 rounded text-base font-black mx-1">{{ $pendingRecruitsCount }}</span> ผู้สมัครงานใหม่ที่รอการยืนยัน
            </h4>
            <a href="{{ route('frontend.my-hires') }}" class="block w-full text-center bg-gradient-to-r from-purple-500 to-fuchsia-500 hover:from-purple-400 hover:to-fuchsia-400 text-white text-xs font-bold py-2 md:py-2.5 rounded-xl transition shadow-lg shadow-purple-500/20">
                ตรวจสอบผู้สมัคร
            </a>
        </div>
    @endif
</div>
@endif

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-\\[slideInUp_0\\.4s_ease-out\\] {
    animation: slideInUp 0.4s ease-out forwards;
}
</style>
