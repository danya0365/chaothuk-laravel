<div class="max-w-4xl mx-auto px-4 py-6 space-y-6">

    @if(!$user)
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🔒</div>
            <p class="text-gray-400 font-medium">กรุณาเข้าสู่ระบบ</p>
        </div>
    @else

    {{-- ═══ Header Card ═══ --}}
    <div class="bg-gray-900 rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-orange-500/5 rounded-full -mr-12 -mt-12"></div>
        <div class="flex items-start gap-5">
            <img src="{{ $user['avatar'] }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-orange-500/50" alt="">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-bold text-white">{{ $user['name'] }}</h1>
                <p class="text-gray-400 text-sm">สมาชิกตั้งแต่ {{ $user['member_since'] }}</p>

                @if($reputation)
                    <div class="flex items-center gap-3 mt-2">
                        {{-- Trust Level Badge --}}
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-bold
                            {{ match($reputation['trust_level']) {
                                'diamond'  => 'bg-purple-500/20 text-purple-300 ring-1 ring-purple-500/40',
                                'platinum' => 'bg-blue-500/20 text-blue-300 ring-1 ring-blue-500/40',
                                'gold'     => 'bg-yellow-500/20 text-yellow-300 ring-1 ring-yellow-500/40',
                                'silver'   => 'bg-gray-400/20 text-gray-300 ring-1 ring-gray-400/40',
                                'bronze'   => 'bg-orange-700/20 text-orange-400 ring-1 ring-orange-700/40',
                                default    => 'bg-gray-700/30 text-gray-400 ring-1 ring-gray-600/40',
                            } }}">
                            {{ $reputation['trust_level_label'] }}
                        </span>

                        {{-- Overall Score --}}
                        <span class="text-yellow-400 font-bold text-lg">
                            ⭐ {{ number_format($reputation['overall_score'], 2) }}
                        </span>
                        <span class="text-gray-500 text-sm">({{ $reputation['total_reviews'] }} รีวิว)</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ Stats Grid ═══ --}}
    @if($reputation)
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @php
            $stats = [
                ['icon' => '✅', 'label' => 'งานเสร็จ', 'value' => $reputation['total_completed_jobs']],
                ['icon' => '📊', 'label' => 'อัตราสำเร็จ', 'value' => number_format($reputation['completion_rate'], 1) . '%'],
                ['icon' => '⚡', 'label' => 'ตอบกลับ', 'value' => $reputation['avg_response_minutes'] . ' นาที'],
                ['icon' => '🔁', 'label' => 'ลูกค้าประจำ', 'value' => $reputation['repeat_customer_count'] . ' คน'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="bg-gray-900 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">{{ $stat['icon'] }}</div>
                <div class="text-white font-bold text-lg">{{ $stat['value'] }}</div>
                <div class="text-gray-500 text-xs">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ═══ Score Breakdown ═══ --}}
    <div class="bg-gray-900 rounded-2xl p-5">
        <h2 class="text-white font-bold mb-4">📊 คะแนนแต่ละมิติ</h2>
        @php
            $dimensions = [
                ['label' => 'คุณภาพงาน', 'score' => $reputation['quality_score'], 'color' => 'bg-orange-500', 'weight' => '35%'],
                ['label' => 'ตรงเวลา', 'score' => $reputation['timeliness_score'], 'color' => 'bg-blue-500', 'weight' => '25%'],
                ['label' => 'การสื่อสาร', 'score' => $reputation['communication_score'], 'color' => 'bg-green-500', 'weight' => '20%'],
                ['label' => 'ความเป็นมืออาชีพ', 'score' => $reputation['professionalism_score'], 'color' => 'bg-purple-500', 'weight' => '20%'],
            ];
        @endphp
        @foreach($dimensions as $dim)
            <div class="mb-3 last:mb-0">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-gray-300 text-sm">{{ $dim['label'] }} <span class="text-gray-600 text-xs">({{ $dim['weight'] }})</span></span>
                    <span class="text-white font-bold text-sm">{{ number_format($dim['score'], 2) }}</span>
                </div>
                <div class="w-full bg-gray-800 rounded-full h-2.5">
                    <div class="{{ $dim['color'] }} h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ ($dim['score'] / 5) * 100 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- ═══ Badges ═══ --}}
    @if(count($badges) > 0)
    <div class="bg-gray-900 rounded-2xl p-5">
        <h2 class="text-white font-bold mb-4">🏅 เหรียญตรา</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach($badges as $badge)
                <div class="text-center p-3 rounded-xl
                    {{ match($badge['level']) {
                        'gold'   => 'bg-yellow-500/10 ring-1 ring-yellow-500/30',
                        'silver' => 'bg-gray-400/10 ring-1 ring-gray-400/30',
                        default  => 'bg-orange-700/10 ring-1 ring-orange-700/30',
                    } }}">
                    <div class="text-2xl mb-1">
                        {{ match($badge['level']) { 'gold' => '🥇', 'silver' => '🥈', default => '🥉' } }}
                    </div>
                    <p class="text-white text-xs font-semibold">{{ $badge['label'] }}</p>
                    <p class="text-gray-500 text-[10px] capitalize">{{ $badge['level'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ Verifications ═══ --}}
    @if(count($verifications) > 0)
    <div class="bg-gray-900 rounded-2xl p-5">
        <h2 class="text-white font-bold mb-4">🛡️ การยืนยันตัวตน</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($verifications as $ver)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm
                    {{ $ver['status'] === 'approved'
                        ? 'bg-green-500/10 text-green-400 ring-1 ring-green-500/30'
                        : ($ver['status'] === 'pending'
                            ? 'bg-yellow-500/10 text-yellow-400 ring-1 ring-yellow-500/30'
                            : 'bg-red-500/10 text-red-400 ring-1 ring-red-500/30') }}">
                    {{ $ver['status'] === 'approved' ? '✅' : ($ver['status'] === 'pending' ? '⏳' : '❌') }}
                    {{ $ver['label'] }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ Rating Distribution ═══ --}}
    @if($reputation && count($ratingDistribution) > 0)
    <div class="bg-gray-900 rounded-2xl p-5">
        <h2 class="text-white font-bold mb-4">📈 การกระจายคะแนน</h2>
        @foreach($ratingDistribution as $star => $data)
            <div class="flex items-center gap-3 mb-2 last:mb-0">
                <span class="text-yellow-400 text-sm font-bold w-8 text-right">{{ $star }}⭐</span>
                <div class="flex-1 bg-gray-800 rounded-full h-3">
                    <div class="bg-yellow-400 h-3 rounded-full transition-all duration-500"
                         style="width: {{ $data['percent'] }}%"></div>
                </div>
                <span class="text-gray-400 text-xs w-16 text-right">{{ $data['count'] }} ({{ $data['percent'] }}%)</span>
            </div>
        @endforeach
    </div>
    @endif

    {{-- ═══ Latest Reviews ═══ --}}
    @if(count($reviews) > 0)
    <div class="bg-gray-900 rounded-2xl p-5">
        <h2 class="text-white font-bold mb-4">💬 รีวิวล่าสุด</h2>
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ $review['reviewer_avatar'] }}" class="w-8 h-8 rounded-full object-cover" alt="">
                        <div class="flex-1 min-w-0">
                            <span class="text-white text-sm font-semibold">{{ $review['reviewer_name'] }}</span>
                            @if($review['verified'])
                                <span class="text-green-400 text-[10px] ml-1">✅ งานจริง</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="text-yellow-400 text-sm font-bold">⭐ {{ $review['overall_rating'] }}</span>
                            <span class="text-gray-600 text-xs">{{ $review['date'] }}</span>
                        </div>
                    </div>

                    {{-- Mini dimension bars --}}
                    <div class="grid grid-cols-4 gap-2 mb-2">
                        @php
                        $miniDims = [
                            ['l' => 'คุณภาพ', 'v' => $review['quality'], 'c' => 'bg-orange-500'],
                            ['l' => 'เวลา', 'v' => $review['timeliness'], 'c' => 'bg-blue-500'],
                            ['l' => 'สื่อสาร', 'v' => $review['communication'], 'c' => 'bg-green-500'],
                            ['l' => 'มืออาชีพ', 'v' => $review['professionalism'], 'c' => 'bg-purple-500'],
                        ];
                        @endphp
                        @foreach($miniDims as $md)
                            <div>
                                <div class="text-gray-500 text-[10px] mb-0.5">{{ $md['l'] }}</div>
                                <div class="w-full bg-gray-800 rounded-full h-1.5">
                                    <div class="{{ $md['c'] }} h-1.5 rounded-full" style="width:{{ ($md['v']/5)*100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($review['comment'])
                        <p class="text-gray-300 text-sm">{{ $review['comment'] }}</p>
                    @endif
                    @if($review['response'])
                        <div class="mt-2 ml-4 pl-3 border-l-2 border-orange-500/30">
                            <p class="text-orange-400 text-[11px] font-semibold mb-0.5">ตอบกลับ:</p>
                            <p class="text-gray-400 text-sm">{{ $review['response'] }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif

</div>
