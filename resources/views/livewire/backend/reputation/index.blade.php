<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                    ระบบเหรียญและชื่อเสียง (Reputation & Badges)
                </h2>
                <p class="text-gray-400 text-sm mt-1">
                    ตรวจสอบระดับความน่าเชื่อถือ (Trust Level) และคะแนนภาพรวมของผู้ใช้งาน
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('backend.reputations.reviews.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-colors border border-slate-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                    ตรวจสอบประวัติการรีวิว
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row gap-4">
            <div class="relative flex-2 md:w-1/3">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="ค้นหาชื่อ หรือ อีเมลผู้ใช้..."
                    class="pl-10 pr-4 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 placeholder-gray-500 w-full transition-all"
                >
            </div>
            
            <div class="w-full sm:w-48">
                <select wire:model.live="trustFilter" class="w-full pl-3 pr-10 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 transition-all">
                    <option value="">-- ทุก Trust Level --</option>
                    @foreach($trustLevels as $level)
                        <option value="{{ $level }}">{{ $level }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full sm:w-56">
                <select wire:model.live="badgeFilter" class="w-full px-3 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 transition-all">
                    <option value="">-- ทุกเหรียญรางวัล --</option>
                    @foreach($badgeTypes as $badge)
                        <option value="{{ $badge }}">{{ $badge }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-900/50 text-gray-400 text-sm border-b border-slate-700/50">
                            <th class="py-4 px-6 font-medium">ผู้ใช้งาน</th>
                            <th class="py-4 px-6 font-medium">คะแนนรวม / Trust Level</th>
                            <th class="py-4 px-6 font-medium text-center">อัตรารับงาน (Completion)</th>
                            <th class="py-4 px-6 font-medium">เหรียญรางวัล (Badges)</th>
                            <th class="py-4 px-6 font-medium text-right">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse ($reputations as $repo)
                            <tr class="hover:bg-slate-800/50 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $repo->user->getAvatar() }}" alt="" class="w-10 h-10 rounded-full border border-slate-700 object-cover">
                                        <div>
                                            <a href="{{ route('backend.users.show', $repo->user_id) }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                                {{ $repo->user->name }}
                                            </a>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                งานที่สำเร็จ: {{ number_format($repo->total_completed_jobs) }} | ยกเลิก: {{ number_format($repo->total_cancelled_jobs) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-200">
                                            {{ number_format($repo->overall_score, 1) }}
                                        </span>
                                        <span class="text-xs text-gray-500">/ 5.0</span>
                                        
                                        @php
                                            $trustColors = [
                                                'new' => 'bg-slate-100/10 text-slate-400 border-slate-500/20',
                                                'bronze' => 'bg-orange-900/20 text-orange-400 border-orange-500/20',
                                                'silver' => 'bg-gray-400/10 text-gray-300 border-gray-400/20',
                                                'gold' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                                'platinum' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                                'diamond' => 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20',
                                            ];
                                            $colorClass = $trustColors[$repo->trust_level] ?? $trustColors['new'];
                                        @endphp
                                        
                                        <div class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border {{ $colorClass }}">
                                            {{ $repo->trust_level_label }}
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center w-full max-w-[120px] gap-2">
                                            <div class="w-full bg-slate-700/50 rounded-full h-1.5 shrink-0 overflow-hidden">
                                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $repo->completion_rate }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-300 w-8">{{ number_format($repo->completion_rate) }}%</span>
                                        </div>
                                        <div class="text-[10px] text-gray-500 mt-1">
                                            ตอบกลับเฉลี่ย {{ $repo->avg_response_minutes }} นาที
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($repo->user->badges as $badge)
                                            <span title="{{ $badge->badge_type }} (Lv.{{ $badge->badge_level }})" class="inline-flex items-center justify-center w-7 h-7 rounded bg-slate-800 border border-slate-700 shadow-inner text-sm">
                                                {{ explode(' ', $badge->label)[0] ?? '🏅' }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-600">-</span>
                                        @endforelse
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6 text-right">
                                    <button type="button" class="p-2 text-slate-400 hover:text-indigo-400 transition-colors rounded-lg hover:bg-slate-800/50">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 px-6 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.866 8.284 8.284 0 0 0 3 2.48Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm">ไม่พบข้อมูลชื่อเสียงผู้ใช้งาน</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($reputations->hasPages())
                <div class="p-4 border-t border-slate-700/50 bg-slate-900/20">
                    {{ $reputations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
