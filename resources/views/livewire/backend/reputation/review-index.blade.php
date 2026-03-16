<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('backend.reputations.index') }}" class="p-2 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 text-gray-400 hover:text-white transition-colors border border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                        ประวัติการรีวิว (Reputation Reviews)
                    </h2>
                    <p class="text-gray-400 text-sm mt-1">
                        ตรวจสอบประวัติการให้คะแนนและคำวิจารณ์ระหว่างผู้ใช้งาน
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row gap-4">
            <div class="relative flex-2 md:w-1/2">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="ค้นหาข้อความรีวิว, ชื่อผู้รีวิว หรือผู้ถูกรีวิว..."
                    class="pl-10 pr-4 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 placeholder-gray-500 w-full transition-all"
                >
            </div>
            
            <div class="w-full sm:w-48">
                <select wire:model.live="ratingFilter" class="w-full pl-3 pr-10 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 transition-all">
                    <option value="">-- ทุกระดับคะแนน --</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4">⭐⭐⭐⭐ (4)</option>
                    <option value="3">⭐⭐⭐ (3)</option>
                    <option value="2">⭐⭐ (2)</option>
                    <option value="1">⭐ (1)</option>
                </select>
            </div>
            
            <div class="w-full sm:w-48">
                <select wire:model.live="typeFilter" class="w-full px-3 py-2 bg-slate-900/50 border border-slate-700/50 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-lg text-sm text-gray-300 transition-all">
                    <option value="">-- ทุกประเภทงาน --</option>
                    <option value="App\Models\WorkSession">เช่าตรง (Direct)</option>
                    <option value="App\Models\RecruitBooking">ประกาศจ้าง (Recruit)</option>
                </select>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-900/50 text-gray-400 text-sm border-b border-slate-700/50">
                            <th class="py-4 px-6 font-medium">ผู้รีวิว (Reviewer)</th>
                            <th class="py-4 px-6 font-medium">ผู้ถูกรีวิว (Reviewee)</th>
                            <th class="py-4 px-6 font-medium">คะแนนรวม</th>
                            <th class="py-4 px-6 font-medium w-1/3">ข้อความรีวิว</th>
                            <th class="py-4 px-6 font-medium text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse ($reviews as $review)
                            <tr class="hover:bg-slate-800/50 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $review->reviewer?->getAvatar() ?? asset('images/default-avatar.png') }}" alt="" class="w-8 h-8 rounded-full bg-slate-700 object-cover">
                                        <div>
                                            <a href="{{ $review->reviewer ? route('backend.users.show', $review->reviewer_id) : '#' }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                                {{ $review->reviewer?->name ?? 'Deleted User' }}
                                            </a>
                                            <div class="text-[10px] text-gray-500 mt-0.5">
                                                {{ $review->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $review->reviewee?->getAvatar() ?? asset('images/default-avatar.png') }}" alt="" class="w-8 h-8 rounded-full bg-slate-700 object-cover">
                                        <div>
                                            <a href="{{ $review->reviewee ? route('backend.users.show', $review->reviewee_id) : '#' }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">
                                                {{ $review->reviewee?->name ?? 'Deleted User' }}
                                            </a>
                                            <div class="text-[10px] text-gray-500 mt-0.5">
                                                @if($review->booking_type === 'App\Models\WorkSession')
                                                    งานเช่าตรง (Direct)
                                                @else
                                                    ประกาศจ้าง (Recruit)
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->overall_rating ? 'text-yellow-400' : 'text-slate-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                                </svg>
                                            @endfor
                                            <span class="text-xs font-bold text-gray-200 ml-1">{{ number_format($review->overall_rating, 1) }}</span>
                                        </div>
                                        <div class="flex gap-2 text-[10px] text-gray-500">
                                            <span title="คุณภาพ">Q: {{ $review->quality_rating }}</span>
                                            <span title="เวลา">T: {{ $review->timeliness_rating }}</span>
                                            <span title="การสื่อสาร">C: {{ $review->communication_rating }}</span>
                                            <span title="ความเป็นมืออาชีพ">P: {{ $review->professionalism_rating }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-300">
                                        @if($review->comment)
                                            "{{ $review->comment }}"
                                        @else
                                            <span class="text-gray-500 italic">ไม่มีข้อความรีวิวเพิ่มเติม</span>
                                        @endif
                                    </div>
                                    @if($review->response)
                                        <div class="mt-2 pl-3 border-l-2 border-slate-600">
                                            <div class="text-[10px] text-gray-500 font-medium mb-0.5">การตอบกลับ:</div>
                                            <div class="text-xs text-gray-400">"{{ $review->response }}"</div>
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="py-4 px-6 text-right">
                                    <button 
                                        type="button" 
                                        wire:click="deleteReview({{ $review->id }})"
                                        wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการซ่อนรีวิวนี้จากการแสดงผล?"
                                        class="p-2 text-slate-400 hover:text-red-400 transition-colors rounded-lg hover:bg-slate-800/50"
                                        title="ลบ/ซ่อนรีวิว"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.436 3 12c0 1.202.274 2.342.767 3.364l-1.522 3.805a.75.75 0 0 0 .964.964l3.805-1.522A8.906 8.906 0 0 0 12 20.25Z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm">ไม่พบประวัติการรีวิว</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($reviews->hasPages())
                <div class="p-4 border-t border-slate-700/50 bg-slate-900/20">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
