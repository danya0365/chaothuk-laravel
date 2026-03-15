<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-red-400 to-orange-400 bg-clip-text text-transparent">
                จัดการเซสชัน (Active Sessions)
            </h2>
            <p class="text-gray-400 text-sm mt-1">
                ตรวจสอบและจัดการการเข้าสู่ระบบของผู้ใช้งานในขณะนี้
            </p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-xl flex items-start gap-3 text-emerald-400">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="p-4 bg-red-500/10 border border-red-500/50 rounded-xl flex items-start gap-3 text-red-400">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Card -->
    <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl overflow-hidden">
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-700/50 flex flex-col sm:flex-row gap-4 justify-between items-center bg-slate-800/20">
            <!-- Search -->
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="w-full pl-10 pr-4 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-red-500/50 focus:ring-1 focus:ring-red-500/50 rounded-xl text-sm text-gray-200 transition-all placeholder-gray-500"
                    placeholder="ค้นหาชื่อ, อีเมล, หรือ IP Address...">
            </div>
            
            <div class="text-xs text-gray-400 font-medium px-3 py-1.5 bg-slate-900/50 rounded-lg border border-slate-700/50 flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                เชื่อมต่อปัจจุบัน: {{ $sessions->total() }} เซสชัน
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/40 border-b border-slate-700/50 text-xs uppercase tracking-wider text-gray-400">
                        <th class="px-6 py-4 font-medium">ผู้ใช้งาน</th>
                        <th class="px-6 py-4 font-medium">IP Address</th>
                        <th class="px-6 py-4 font-medium">อุปกรณ์ / เบราว์เซอร์</th>
                        <th class="px-6 py-4 font-medium">ใช้งานล่าสุด</th>
                        <th class="px-6 py-4 font-medium text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-700/20 transition-colors {{ $session->id === session()->getId() ? 'bg-indigo-900/10' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($session->user)
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-lg flex-shrink-0">
                                            {{ mb_substr($session->user->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-200">
                                                {{ $session->user->first_name }} {{ $session->user->last_name }}
                                                @if($session->id === session()->getId())
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                                        คุณ (Current)
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $session->user->email }}</div>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center text-gray-400 flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div class="text-sm text-gray-500 italic">Unauthenticated User</div>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md bg-slate-900/50 border border-slate-700/50 text-xs text-gray-300 font-mono">
                                    {{ $session->ip_address ?? 'Unknown' }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-400 max-w-xs truncate" title="{{ $session->user_agent }}">
                                    {{ $session->user_agent ?? 'Unknown Agent' }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300">
                                    {{ $session->getLastActivityDate() }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 text-right">
                                @if($session->id !== session()->getId())
                                    <button 
                                        wire:click="deleteSession('{{ $session->id }}')"
                                        wire:confirm="คุณแน่ใจหรือไม่ที่จะเตะผู้ใช้นี้ออกจากระบบ? ผู้ใช้จะต้องล็อคอินใหม่"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-colors border border-red-500/20 hover:border-red-500"
                                        title="เตะออกจากระบบ (Kick)"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-800 text-slate-500 border border-slate-700 cursor-not-allowed" title="ไม่สามารถเตะตัวเองได้">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <p class="text-sm font-medium">ไม่พบข้อมูลเซสชัน</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-800/20">
                {{ $sessions->links('livewire::backend-pagination') }}
            </div>
        @endif
    </div>
</div>
