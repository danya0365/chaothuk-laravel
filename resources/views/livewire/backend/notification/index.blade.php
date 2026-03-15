<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                    ประวัติการแจ้งเตือน (Notifications)
                </h2>
                <p class="text-gray-400 text-sm mt-1">
                    ประวัติการส่งแจ้งเตือน (Push/In-App) ทั้งหมดในระบบ
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('backend.notifications.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    สร้างประกาศ (Broadcast)
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="ค้นหาจากหัวข้อ, รายละเอียด หรือผู้รับ..."
                    class="pl-10 pr-4 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 placeholder-gray-500 w-full transition-all"
                >
            </div>
            
            <div class="w-full sm:w-64">
                <select wire:model.live="typeFilter" class="w-full pl-3 pr-10 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 transition-all">
                    <option value="">-- กรองประเภททั้งหมด --</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/50 text-gray-400 text-sm border-b border-slate-700/50">
                            <th class="py-4 px-6 font-medium">หัวข้อ (Title) / ประเภท</th>
                            <th class="py-4 px-6 font-medium">ผู้รับ (Recipient)</th>
                            <th class="py-4 px-6 font-medium text-center">สถานะอ่านแล้ว</th>
                            <th class="py-4 px-6 font-medium text-right">เวลาที่ส่ง</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse ($notifications as $notification)
                            <tr class="hover:bg-slate-800/50 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center shrink-0 mt-1">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-200">
                                                {{ $notification->title }}
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1 line-clamp-1 w-64 lg:w-96">
                                                @if(is_array($notification->details) && isset($notification->details['body']))
                                                    {{ $notification->details['body'] }}
                                                @else
                                                    {{ json_encode($notification->details, JSON_UNESCAPED_UNICODE) }}
                                                @endif
                                            </div>
                                            <div class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-700/50 text-slate-300 border border-slate-600/50">
                                                {{ $notification->notification_type }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($notification->author)
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $notification->author->getAvatar() }}" alt="" class="w-8 h-8 rounded-full bg-slate-700 object-cover">
                                            <div>
                                                <a href="{{ route('backend.users.show', $notification->author_id) }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                                    {{ $notification->author->name }}
                                                </a>
                                                <div class="text-xs text-gray-500">{{ $notification->author->email }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 italic">ถึงทุกคน (Broadcast) / เลิกใช้งาน</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($notification->is_read)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-400" title="อ่านแล้ว">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-500/10 text-slate-400" title="ยังไม่อ่าน">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-400 text-right whitespace-nowrap">
                                    <div>{{ $notification->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $notification->created_at->format('H:i') }} น.</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 px-6 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                            </svg>
                                        </div>
                                        <p class="text-sm">ไม่พบประวัติการแจ้งเตือน</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($notifications->hasPages())
                <div class="p-4 border-t border-slate-700/50 bg-slate-900/20">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
