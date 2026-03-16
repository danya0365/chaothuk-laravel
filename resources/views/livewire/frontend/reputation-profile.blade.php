<div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 md:py-6 space-y-4 md:space-y-5">

    @if(!$user)
        <div class="text-center py-10 md:py-20">
            <div class="text-5xl md:text-6xl mb-3 md:mb-4">🔒</div>
            <p class="text-gray-400 font-medium">ไม่พบผู้ใช้งาน</p>
        </div>
    @else

    {{-- ═══════════════════════════════════════════════════════════════════
         HERO — Profile Header
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-br from-gray-900 via-gray-900 to-orange-900/20 rounded-2xl p-4 md:p-6 relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-56 h-56 bg-orange-500/5 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-orange-500/3 rounded-full -ml-8 -mb-8"></div>

        <div class="flex items-start gap-3 md:gap-5 relative z-10 flex-col sm:flex-row items-center sm:items-start text-center sm:text-left">
            <x-avatar :src="$user['avatar'] ?? null" :name="$user['full_name'] ?? $user['name'] ?? 'U'" size="2xl" :border="false" class="ring-3 ring-orange-500/50 shadow-lg shadow-orange-500/10 shrink-0" />
            <div class="flex-1 min-w-0 flex flex-col items-center sm:items-start">
                <h1 class="text-xl md:text-2xl font-bold text-white">{{ $user['full_name'] ?? $user['name'] }}</h1>

                <div class="flex items-center justify-center sm:justify-start gap-2 md:gap-3 text-xs md:text-sm text-gray-400 mt-1 md:mt-1.5 flex-wrap">
                    <span>📅 สมาชิก {{ $user['member_since'] }}</span>
                    @if($user['location'])
                        <span>📍 {{ $user['location'] }}</span>
                    @endif
                    <span>⏱️ {{ $user['member_days'] }} วัน</span>
                </div>

                {{-- Trust Level + Overall Score --}}
                <div class="flex items-center justify-center sm:justify-start gap-2 md:gap-3 mt-3 md:mt-4 flex-wrap">
                    @if($reputation)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-xs md:text-sm font-bold
                            {{ match($reputation['trust_level'] ?? '') {
                                'diamond'  => 'bg-purple-500/20 text-purple-300 ring-1 ring-purple-500/40',
                                'platinum' => 'bg-blue-500/20 text-blue-300 ring-1 ring-blue-500/40',
                                'gold'     => 'bg-yellow-500/20 text-yellow-300 ring-1 ring-yellow-500/40',
                                'silver'   => 'bg-gray-400/20 text-gray-300 ring-1 ring-gray-400/40',
                                'bronze'   => 'bg-orange-700/20 text-orange-400 ring-1 ring-orange-700/40',
                                default    => 'bg-gray-700/30 text-gray-400 ring-1 ring-gray-600/40',
                            } }}">
                            {{ match($reputation['trust_level'] ?? '') {
                                'diamond' => '💎', 'platinum' => '👑', 'gold' => '🥇', 'silver' => '🥈', 'bronze' => '🥉', default => '🆕'
                            } }}
                            {{ $reputation['trust_level_label'] ?? 'ใหม่' }}
                        </span>
                    @endif

                    @if($stats['avg_rating'] > 0)
                        <span class="text-yellow-400 font-bold text-base md:text-lg flex items-center gap-1">
                            ⭐ {{ number_format($stats['avg_rating'], 1) }}
                        </span>
                        <span class="text-gray-500 text-xs md:text-sm">({{ $stats['total_reviews'] }} รีวิว)</span>
                    @endif
                </div>

                {{-- Verification badges inline --}}
                @if(count($verifications) > 0)
                    <div class="flex flex-wrap justify-center sm:justify-start gap-1.5 mt-3 md:mt-4">
                        @foreach($verifications as $ver)
                            @if($ver['status'] === 'approved')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-500/10 text-green-400 text-[11px] font-semibold rounded-full ring-1 ring-green-500/20">
                                    ✅ {{ $ver['label'] }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         STATS GRID — Key Numbers
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
            $statCards = [
                ['icon' => '📦', 'value' => $stats['total_works'], 'label' => 'งานที่โพสต์', 'color' => 'text-orange-400'],
                ['icon' => '✅', 'value' => $stats['completed_jobs'], 'label' => 'งานสำเร็จ', 'color' => 'text-green-400'],
                ['icon' => '📊', 'value' => $stats['completion_rate'] . '%', 'label' => 'อัตราสำเร็จ', 'color' => $stats['completion_rate'] >= 80 ? 'text-green-400' : ($stats['completion_rate'] >= 50 ? 'text-yellow-400' : 'text-red-400')],
                ['icon' => '❤️', 'value' => $stats['total_likes'], 'label' => 'ถูกใจ', 'color' => 'text-red-400'],
            ];
        @endphp
        @foreach($statCards as $sc)
            <div class="bg-gray-900 rounded-xl p-3 md:p-4 text-center">
                <div class="text-xl md:text-2xl mb-1">{{ $sc['icon'] }}</div>
                <div class="text-lg md:text-xl font-black {{ $sc['color'] }}">{{ $sc['value'] }}</div>
                <div class="text-gray-500 text-[10px] md:text-xs mt-0.5">{{ $sc['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         SCORE BREAKDOWN (if reputation exists)
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($reputation)
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        <h2 class="text-white font-bold mb-3 md:mb-4 flex items-center gap-2 text-sm md:text-base">📊 คะแนนรายมิติ</h2>
        @php
            $dims = [
                ['label' => 'คุณภาพงาน', 'score' => $reputation['quality_score'], 'color' => 'bg-orange-500', 'weight' => '35%'],
                ['label' => 'ตรงเวลา', 'score' => $reputation['timeliness_score'], 'color' => 'bg-blue-500', 'weight' => '25%'],
                ['label' => 'การสื่อสาร', 'score' => $reputation['communication_score'], 'color' => 'bg-green-500', 'weight' => '20%'],
                ['label' => 'ความเป็นมืออาชีพ', 'score' => $reputation['professionalism_score'], 'color' => 'bg-purple-500', 'weight' => '20%'],
            ];
        @endphp
        @foreach($dims as $dim)
            <div class="mb-2 md:mb-3 last:mb-0">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-gray-300 text-xs md:text-sm">{{ $dim['label'] }} <span class="text-gray-600 text-[10px] md:text-xs">({{ $dim['weight'] }})</span></span>
                    <span class="text-white font-bold text-xs md:text-sm">{{ number_format($dim['score'], 2) }}</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2.5">
                    <div class="{{ $dim['color'] }} h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ ($dim['score'] / 5) * 100 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         BADGES
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(count($badges) > 0)
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        <h2 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">🏅 เหรียญตราที่ได้รับ</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 md:gap-3">
            @foreach($badges as $badge)
                <div class="text-center p-2.5 md:p-3 rounded-xl
                    {{ match($badge['level']) {
                        'gold'   => 'bg-yellow-500/10 ring-1 ring-yellow-500/30',
                        'silver' => 'bg-gray-400/10 ring-1 ring-gray-400/30',
                        default  => 'bg-orange-700/10 ring-1 ring-orange-700/30',
                    } }}">
                    <div class="text-xl md:text-2xl mb-0.5 md:mb-1">
                        {{ match($badge['level']) { 'gold' => '🥇', 'silver' => '🥈', default => '🥉' } }}
                    </div>
                    <p class="text-white text-[10px] md:text-xs font-semibold">{{ $badge['label'] }}</p>
                    <p class="text-gray-500 text-[9px] md:text-[10px] capitalize mt-0.5">ระดับ {{ $badge['level'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         WORKS PORTFOLIO — The Service Catalog
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(count($works) > 0)
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        <div class="flex items-center justify-between mb-3 md:mb-4">
            <h2 class="text-white font-bold text-sm md:text-base">📦 งานบริการ ({{ $stats['total_works'] }})</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 md:gap-3">
            @foreach($works as $w)
                <a href="{{ route('frontend.works.show', $w['id']) }}"
                   class="group bg-gray-800 rounded-xl overflow-hidden hover:ring-2 hover:ring-orange-500/40 transition">
                    <div class="aspect-video bg-gray-700">
                        <img src="{{ $w['image'] ?? 'https://picsum.photos/seed/'.$w['id'].'/400/225' }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                    </div>
                    <div class="p-2.5 md:p-3">
                        <p class="text-white text-[11px] md:text-xs font-semibold truncate group-hover:text-orange-400 transition">{{ $w['title'] }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-orange-400 text-sm font-bold">฿{{ number_format($w['price']) }}</span>
                            @if($w['rating'] > 0)
                                <span class="text-yellow-400 text-[11px]">⭐ {{ number_format($w['rating'], 1) }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-gray-500 text-[10px]">📍 {{ $w['province'] ?? '-' }}</span>
                            @if($w['status'] === 'stand-by')
                                <span class="text-green-400 text-[10px]">● พร้อม</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         RATING DISTRIBUTION
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(count($ratingDistribution) > 0 && array_sum(array_column($ratingDistribution, 'count')) > 0)
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        <h2 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">📈 สรุปคะแนนรีวิว</h2>
        <div class="flex items-start gap-4 md:gap-6">
            {{-- Big score --}}
            <div class="text-center flex-shrink-0">
                <div class="text-3xl md:text-4xl font-black text-yellow-400">{{ number_format($stats['avg_rating'], 1) }}</div>
                <div class="flex gap-0.5 justify-center mt-0.5 md:mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-xs md:text-sm {{ $i <= round($stats['avg_rating']) ? 'text-yellow-400' : 'text-gray-700' }}">★</span>
                    @endfor
                </div>
                <p class="text-gray-500 text-[10px] md:text-xs mt-1">{{ $stats['total_reviews'] }} รีวิว</p>
            </div>
            {{-- Bars --}}
            <div class="flex-1 space-y-1 md:space-y-1.5">
                @foreach($ratingDistribution as $star => $data)
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="text-yellow-400 text-[10px] md:text-xs font-bold w-3 md:w-4 text-right">{{ $star }}</span>
                        <span class="text-yellow-400 text-[10px] md:text-xs">★</span>
                        <div class="flex-1 bg-gray-800 rounded-full h-1.5 md:h-2.5">
                            <div class="bg-yellow-400 h-1.5 md:h-2.5 rounded-full transition-all duration-500"
                                 style="width: {{ $data['percent'] }}%"></div>
                        </div>
                        <span class="text-gray-500 text-[9px] md:text-[11px] w-12 md:w-14 text-right">{{ $data['count'] }} ({{ $data['percent'] }}%)</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         LATEST REVIEWS
    ═══════════════════════════════════════════════════════════════════ --}}
    @if($paginatedReviews->count() > 0)
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        <h2 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">💬 รีวิวล่าสุด</h2>
        <div class="space-y-3 md:space-y-4">
            @foreach($paginatedReviews as $review)
                <div class="border-b border-gray-800 pb-3 md:pb-4 last:border-0 last:pb-0">
                    <div class="flex items-start md:items-center gap-2 md:gap-3 mb-1.5 md:mb-2 text-center md:text-left">
                        <x-avatar :src="$review->reviewer_avatar ?? null" :name="$review->reviewer_name ?? 'U'" size="w-7 h-7 md:w-9 md:h-9" :border="false" />
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 md:gap-2">
                                <span class="text-white text-xs md:text-sm font-semibold truncate">{{ $review->reviewer_name }}</span>
                                @if($review->verified ?? false)
                                    <span class="text-green-400 text-[9px] md:text-[10px] bg-green-500/10 px-1 md:px-1.5 py-0.5 rounded-full whitespace-nowrap">✅ งานจริง</span>
                                @endif
                            </div>
                            <span class="text-gray-600 text-[9px] md:text-[10px] text-left block">{{ $review->date ?? ($review->created_at ? $review->created_at->diffForHumans() : '') }}</span>
                        </div>
                        <div class="flex items-center gap-0.5 flex-shrink-0 mt-0.5 md:mt-0">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-xs md:text-sm {{ $i <= ($review->overall_rating ?? 0) ? 'text-yellow-400' : 'text-gray-700' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    {{-- Mini dimension bars (only if multi-dim data) --}}
                    @if(($review->quality ?? 0) > 0)
                    <div class="grid grid-cols-4 gap-1.5 md:gap-2 mb-1.5 md:mb-2">
                        @php
                            $miniDims = [
                                ['l' => 'คุณภาพ', 'v' => $review->quality, 'c' => 'bg-orange-500'],
                                ['l' => 'เวลา', 'v' => $review->timeliness, 'c' => 'bg-blue-500'],
                                ['l' => 'สื่อสาร', 'v' => $review->communication, 'c' => 'bg-green-500'],
                                ['l' => 'มืออาชีพ', 'v' => $review->professionalism, 'c' => 'bg-purple-500'],
                            ];
                        @endphp
                        @foreach($miniDims as $md)
                            <div>
                                <div class="text-gray-500 text-[9px] md:text-[10px] mb-0.5">{{ $md['l'] }}</div>
                                <div class="w-full bg-gray-800 rounded-full h-1 md:h-1.5">
                                    <div class="{{ $md['c'] }} h-1 md:h-1.5 rounded-full" style="width:{{ ($md['v']/5)*100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    @if($review->comment ?? null)
                        <p class="text-gray-300 text-xs md:text-sm mt-1">{{ $review->comment }}</p>
                    @endif
                    @if($review->response ?? null)
                        <div class="mt-1.5 md:mt-2 ml-2 pl-2 md:ml-4 md:pl-3 border-l-2 border-orange-500/30">
                            <p class="text-orange-400 text-[10px] md:text-[11px] font-semibold mb-0.5">ตอบกลับ:</p>
                            <p class="text-gray-400 text-xs md:text-sm">{{ $review->response }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="mt-3 md:mt-4 text-xs">
            {{ $paginatedReviews->links() }}
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         VERIFICATIONS (full list for pending too)
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(count($verifications) > 0)
    <div class="bg-gray-900 rounded-2xl p-4 md:p-5">
        <h2 class="text-white font-bold mb-3 md:mb-4 text-sm md:text-base">🛡️ การยืนยันตัวตน</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 md:gap-2">
            @foreach($verifications as $ver)
                <div class="flex items-center gap-1.5 md:gap-2 px-2.5 md:px-3 py-1.5 md:py-2.5 rounded-xl
                    {{ $ver['status'] === 'approved'
                        ? 'bg-green-500/10 ring-1 ring-green-500/20'
                        : ($ver['status'] === 'pending'
                            ? 'bg-yellow-500/10 ring-1 ring-yellow-500/20'
                            : 'bg-red-500/10 ring-1 ring-red-500/20') }}">
                    <span class="text-base md:text-lg">{{ $ver['status'] === 'approved' ? '✅' : ($ver['status'] === 'pending' ? '⏳' : '❌') }}</span>
                    <div>
                        <p class="text-xs md:text-sm font-semibold {{ $ver['status'] === 'approved' ? 'text-green-400' : ($ver['status'] === 'pending' ? 'text-yellow-400' : 'text-red-400') }}">
                            {{ $ver['label'] }}
                        </p>
                        <p class="text-gray-600 text-[9px] md:text-[10px]">
                            {{ $ver['status'] === 'approved' ? 'ยืนยันแล้ว' : ($ver['status'] === 'pending' ? 'รอตรวจสอบ' : 'ไม่ผ่าน') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         EMPTY STATE
    ═══════════════════════════════════════════════════════════════════ --}}
    @if(count($works) === 0 && count($reviews) === 0)
    <div class="text-center py-8 md:py-12 bg-gray-900 rounded-2xl">
        <div class="text-4xl md:text-5xl mb-2 md:mb-3">🌱</div>
        <p class="text-gray-400 text-sm font-medium">ผู้ใช้งานใหม่</p>
        <p class="text-gray-600 text-xs md:text-sm mt-1">ยังไม่มีประวัติการทำงาน</p>
    </div>
    @endif

    @endif

</div>
