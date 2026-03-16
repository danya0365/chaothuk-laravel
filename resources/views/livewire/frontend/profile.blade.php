<div class="max-w-5xl mx-auto px-4 py-8 md:py-12">
    
    {{-- Header Section (Storefront Identity) --}}
    <div class="bg-gradient-to-br from-gray-900 via-gray-900 to-orange-500/5 rounded-2xl md:rounded-3xl p-6 md:p-8 md:pb-12 border border-gray-800/60 shadow-2xl relative overflow-hidden mb-8">
        {{-- Background blur glow --}}
        <div class="absolute -top-32 -right-32 w-64 h-64 bg-orange-500/10 rounded-full blur-[100px]"></div>

        <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-5 w-full">
                {{-- Avatar --}}
                <div class="relative group">
                    <div class="relative w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-gray-900 shadow-xl z-20">
                        <x-avatar :src="$user['avatar']" :name="$user['name']" size="w-full h-full" :border="false" class="object-cover group-hover:scale-105 transition duration-500" />
                    </div>
                </div>

                {{-- User Info --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-black text-white flex items-center justify-center md:justify-start gap-2">
                        {{ $user['name'] }}
                        @if(!empty($verifications))
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        @endif
                    </h1>
                    
                    @if(isset($reputation['trust_level_label']))
                        <div class="mt-1 flex justify-center md:justify-start">
                            <span class="inline-flex items-center gap-1 bg-amber-500/10 text-amber-500 border border-amber-500/20 px-2 py-0.5 rounded textxs font-bold uppercase tracking-wider">
                                🎖️ {{ $reputation['trust_level_label'] }}
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col gap-1.5 mt-3 text-sm text-gray-400 font-medium">
                        @if($user['location'])
                            <p class="flex items-center justify-center md:justify-start gap-1.5">
                                <span class="text-gray-500">📍</span> {{ $user['location'] }}
                            </p>
                        @endif
                        <p class="flex items-center justify-center md:justify-start gap-1.5">
                            <span class="text-gray-500">📅</span> ร่วมงานกับ Chaothuk {{ $user['member_since'] }}
                        </p>
                    </div>

                    @if($user['biography'])
                        <p class="mt-4 text-gray-300 text-sm md:text-base leading-relaxed max-w-2xl bg-black/20 p-3 rounded-lg border border-gray-800/50">
                            {{ $user['biography'] }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            @if(auth()->check() && auth()->id() === $user['id'])
                <div class="flex-shrink-0 w-full md:w-auto flex md:flex-col gap-2">
                    <a href="{{ route('frontend.profile.edit') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded-xl transition border border-gray-700 shadow-sm">
                        ✏️ ล่าโปรไฟล์
                    </a>
                    <a href="{{ route('frontend.my-works') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 font-semibold rounded-xl transition border border-orange-500/30">
                        💼 จัดการงาน
                    </a>
                </div>
            @endif
        </div>
        
        {{-- Floating Stat Bar --}}
        <div class="absolute bottom-0 left-0 right-0 bg-black/40 border-t border-gray-800/50 px-6 py-3 md:flex justify-around items-center hidden backdrop-blur-sm">
            <div class="text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-0.5">ผลงาน</p>
                <p class="text-xl font-black text-white">{{ $stats['total_works'] }}</p>
            </div>
            <div class="w-px h-8 bg-gray-800"></div>
            <div class="text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-0.5">จบคิวสำเร็จ</p>
                <div class="flex items-baseline justify-center gap-1">
                    <p class="text-xl font-black text-green-400">{{ $stats['completed_jobs'] }}</p>
                    <p class="text-[10px] text-green-500/70">/ {{ $stats['total_bookings'] }} คิว</p>
                </div>
            </div>
            <div class="w-px h-8 bg-gray-800"></div>
            <div class="text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-0.5">Rating</p>
                <div class="flex items-center gap-1">
                    <span class="text-xl mb-1">⭐</span>
                    <p class="text-xl font-black text-white">{{ number_format($stats['avg_rating'], 1) }}</p>
                    <p class="text-[10px] text-gray-400 ml-1">({{ $stats['total_reviews'] }})</p>
                </div>
            </div>
            <div class="w-px h-8 bg-gray-800"></div>
            <div class="text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-0.5">Likes</p>
                <p class="text-xl font-black text-red-400">{{ number_format($stats['total_likes']) }}</p>
            </div>
        </div>
    </div>
    
    {{-- Mobile Version of the Stat bar --}}
    <div class="grid grid-cols-2 gap-2 mb-8 md:hidden">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
            <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">ผลงาน</p>
            <p class="text-lg font-black text-white">{{ $stats['total_works'] }}</p>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
            <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">จบคิวสำเร็จ</p>
            <div class="flex items-baseline justify-center gap-1">
                <p class="text-lg font-black text-green-400">{{ $stats['completed_jobs'] }}</p>
                <p class="text-[9px] text-gray-500">/ {{ $stats['total_bookings'] }}</p>
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
            <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Rating</p>
            <div class="flex items-center justify-center gap-1">
                <span class="text-xs mb-0.5">⭐</span>
                <p class="text-lg font-black text-white">{{ number_format($stats['avg_rating'], 1) }}</p>
            </div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 text-center">
            <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Likes</p>
            <p class="text-lg font-black text-red-400">{{ number_format($stats['total_likes']) }}</p>
        </div>
    </div>


    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Main Content: Works --}}
        <div class="flex-1 w-full min-w-0">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">📦 บริการที่รับทำ</h2>
            
            @if(empty($works))
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-10 text-center">
                    <p class="text-4xl mb-3">📭</p>
                    <p class="text-gray-400 font-medium">ผู้ใช้นี้ยังไม่มีงานที่เปิดให้บริการ</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($works as $work)
                        <div class="group bg-gray-900 border border-gray-800 rounded-xl overflow-hidden hover:border-orange-500/50 hover:shadow-[0_0_15px_rgba(249,115,22,0.15)] transition-all duration-300">
                            {{-- Image --}}
                            <a href="{{ route('frontend.works.show', $work['id']) }}" class="block aspect-video bg-gray-800 overflow-hidden relative">
                                <img src="{{ $work['image'] ?? 'https://picsum.photos/seed/'.$work['id'].'/400/300' }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="">
                                
                                @if(isset($work['active_feature']))
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-green-500 text-white px-2 py-0.5 rounded text-[10px] font-bold shadow-lg">🌟 Featured</span>
                                    </div>
                                @endif
                                @if($work['status'] === 'busy')
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-yellow-500 text-white px-2 py-0.5 rounded text-[10px] font-bold shadow-lg">คิวเต็ม</span>
                                    </div>
                                @elseif($work['status'] === 'close')
                                    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] flex items-center justify-center">
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm font-bold shadow-lg shadow-red-500/20">ปิดรับงานชั่วคราว</span>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Details --}}
                            <div class="p-4">
                                <a href="{{ route('frontend.works.show', $work['id']) }}" class="font-semibold text-white group-hover:text-orange-400 transition line-clamp-2 text-sm md:text-base leading-tight">
                                    {{ $work['title'] }}
                                </a>
                                
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-orange-400 font-black text-base md:text-lg">฿{{ number_format($work['price']) }}</p>
                                    <div class="flex items-center gap-1.5 text-[10px] md:text-xs">
                                        @if($work['rating'] > 0)
                                            <span class="text-yellow-400 font-bold">⭐ {{ number_format($work['rating'], 1) }}</span>
                                        @endif
                                        <span class="text-gray-500">❤️ {{ $work['likes'] }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 text-[10px] md:text-xs text-gray-500 flex items-center gap-1">
                                    📍 {{ $work['province'] ?? 'ไม่ระบุ' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


        {{-- Right Sidebar: Reputation & Reviews --}}
        <div class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-6">
            
            {{-- Trust & Safety Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-widest mb-4">🏆 ความน่าเชื่อถือ</h3>
                
                <div class="space-y-3">
                    @forelse($verifications as $v)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400 text-lg">✓</span>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-white">{{ $v['label'] }}</p>
                                <p class="text-xs text-green-400">{{ $v['status'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">ไม่ได้ยืนยันตัวตนเพิ่มเติม</p>
                    @endforelse
                </div>

                @if(!empty($badges))
                    <div class="mt-5 pt-4 border-t border-gray-800">
                        <p class="text-xs font-bold text-gray-400 mb-3">เหรียญรางวัล</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($badges as $b)
                                <div class="flex items-center gap-1.5 bg-gray-800 border border-gray-700 px-2.5 py-1 rounded-lg">
                                    <span class="text-sm">🏅</span>
                                    <span class="text-[10px] font-medium text-gray-300">{{ $b['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Rating Distribution Card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-widest mb-4">📊 คุณภาพงาน</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="text-center">
                        <p class="text-4xl font-black text-white">{{ number_format($stats['avg_rating'], 1) }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $stats['total_reviews'] }} รีวิว</p>
                    </div>
                    <div class="flex-1">
                        @foreach([5,4,3,2,1] as $star)
                            <div class="flex items-center gap-2 mb-1 text-[10px]">
                                <span class="w-3 text-gray-500 text-right">{{ $star }}</span>
                                <span class="text-yellow-500 text-xs">★</span>
                                <div class="flex-1 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-400 rounded-full" 
                                         style="width: {{ $ratingDistribution[$star]['percent'] ?? 0 }}%">
                                    </div>
                                </div>
                                <span class="w-5 text-gray-500 text-right">{{ $ratingDistribution[$star]['percent'] ?? 0 }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                {{-- Completion Rate Progress --}}
                @if($stats['completion_rate'] > 0)
                    <div class="pt-4 border-t border-gray-800">
                        <div class="flex justify-between items-end mb-1">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">อัตราการจบงาน</p>
                            <p class="text-sm font-black text-green-400">{{ $stats['completion_rate'] }}%</p>
                        </div>
                        <div class="h-1.5 w-full bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: {{ $stats['completion_rate'] }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Reviews Feed --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-widest mb-4">💬 เสียงตอบรับ</h3>
                
                <div class="space-y-4">
                    @forelse($paginatedReviews as $review)
                        <div class="pb-4 border-b border-gray-800/60 last:border-0 last:pb-0">
                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ $review->reviewer_avatar }}" class="w-6 h-6 rounded-full bg-gray-800" alt="">
                                <p class="text-xs font-semibold text-gray-300">{{ $review->reviewer_name }}</p>
                                <span class="text-gray-600 text-[10px] ml-auto">{{ $review->date }}</span>
                            </div>
                            <div class="flex items-center gap-0.5 text-xs text-yellow-400 mb-1">
                                {!! str_repeat('★', floor($review->overall_rating)) !!}{!! str_repeat('<span class="text-gray-700">★</span>', 5 - floor($review->overall_rating)) !!}
                                @if($review->verified)
                                    <span class="ml-1 text-[9px] bg-green-500/10 text-green-400 px-1 rounded">✅ ยืนยันจ้างงาน</span>
                                @endif
                            </div>
                            <p class="text-gray-400 text-xs leading-relaxed italic line-clamp-3">"{!! nl2br(e($review->comment)) !!}"</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-2xl mb-1">💭</p>
                            <p class="text-xs text-gray-500">ยังไม่มีเสียงตอบรับ</p>
                        </div>
                    @endforelse
                </div>
                
                @if($paginatedReviews->hasPages())
                    <div class="mt-4 pt-4 border-t border-gray-800">
                        {{ $paginatedReviews->links('vendor.livewire.simple-tailwind') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
