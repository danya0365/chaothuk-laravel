<div class="max-w-7xl mx-auto px-3 sm:px-4 py-6 md:py-10">

    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-white">🔮 Me Explorer</h1>
        <p class="mt-1 text-sm md:text-base text-gray-400">ทดสอบ /api/me/* endpoints ด้วย Sanctum Token</p>
    </div>

    @if(!session('api_token'))
        <div class="rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-6 md:p-8 text-center">
            <span class="text-4xl mb-3 md:mb-4 block">🔒</span>
            <p class="text-yellow-400 text-sm md:text-base font-medium mb-1 md:mb-2">ต้องมี API Token ก่อน</p>
            <p class="text-xs md:text-sm text-gray-400 mb-4">ไปที่ Dashboard และ Login เพื่อรับ Sanctum Token</p>
            <a href="{{ route('frontend.dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 md:px-5 md:py-2.5 rounded-xl text-xs md:text-sm font-semibold bg-gradient-to-r from-orange-500 to-rose-600 text-white hover:opacity-90 transition-all">
                🏠 ไป Dashboard
            </a>
        </div>
    @else

        {{-- Tabs --}}
        <div class="flex gap-1 p-1 bg-white/5 rounded-xl border border-white/10 mb-4 md:mb-6 overflow-x-auto">
            @foreach([
                ['id' => 'profile',  'label' => '👤 Profile',   'endpoint' => '/api/me'],
                ['id' => 'works',    'label' => '💼 My Works',  'endpoint' => '/api/me/works'],
                ['id' => 'recruits', 'label' => '📋 My Recruits','endpoint' => '/api/me/recruits'],
                ['id' => 'bookings', 'label' => '📅 Bookings',  'endpoint' => '/api/me/work-bookings'],
                ['id' => 'posts',    'label' => '⭐ My Reviews', 'endpoint' => '/api/me/posts'],
            ] as $tab)
                <button wire:click="switchTab('{{ $tab['id'] }}')"
                        class="shrink-0 flex flex-col items-center px-3 py-2 md:px-4 md:py-2.5 rounded-lg text-xs md:text-sm transition-all
                               {{ $activeTab === $tab['id'] ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    {{ $tab['label'] }}
                    <span class="text-[10px] md:text-xs text-gray-600 font-mono mt-0.5">{{ $tab['endpoint'] }}</span>
                </button>
            @endforeach
        </div>

        @if($errorMessage)
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-sm text-rose-400">⚠️ {{ $errorMessage }}</div>
        @endif

        {{-- Content Area --}}
        <div wire:loading.class="opacity-60" class="transition-opacity">

        @if($activeTab === 'profile' && $profile)
            @php $user = $profile['data'] ?? null; @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                {{-- Profile Card --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 md:p-6">
                    <div class="flex items-center gap-3 md:gap-4 mb-4 md:mb-5">
                        <x-avatar :src="$user['photo_url'] ?? null" :name="$user['full_name'] ?? 'U'" size="lg" :border="false" class="ring-2 ring-white/10 md:w-16 md:h-16 w-12 h-12" />
                        <div>
                            <h2 class="text-base md:text-lg font-bold text-white">{{ $user['full_name'] ?? '-' }}</h2>
                            <p class="text-xs md:text-sm text-gray-400">{{ $user['email'] ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @foreach(['location' => '📍 Location', 'mobile_phone' => '📞 Phone'] as $key => $label)
                            @if(!empty($user[$key]))
                                <div class="flex items-center gap-2 md:gap-3 py-1.5 md:py-2 px-2.5 md:px-3 rounded-lg bg-white/5">
                                    <span class="text-[10px] md:text-xs text-gray-400">{{ $label }}</span>
                                    <span class="text-xs md:text-sm text-white ml-auto">{{ $user[$key] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                {{-- Raw --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 md:p-5">
                    <p class="text-[10px] md:text-xs text-gray-500 mb-2 font-mono">GET /api/me → Raw JSON</p>
                    <pre class="text-[10px] md:text-xs text-gray-300 overflow-auto max-h-72 leading-relaxed">{{ json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>

        @elseif(in_array($activeTab, ['works', 'recruits', 'bookings', 'posts']))
            @php
                $data = match($activeTab) {
                    'works'    => $myWorks,
                    'recruits' => $myRecruits,
                    'bookings' => $myBookings,
                    'posts'    => $myPosts,
                    default    => null,
                };
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Cards --}}
                <div>
                    @if($data && isset($data['data']) && count($data['data']))
                        <div class="space-y-3">
                            @foreach($data['data'] as $item)
                                <div class="p-4 rounded-xl border border-white/10 bg-white/5">
                                    <p class="text-sm font-medium text-white">
                                        {{ $item['title'] ?? $item['content'] ?? $item['customer_message'] ?? $item['worker_message'] ?? 'ID: ' . $item['id'] }}
                                    </p>
                                    @if(isset($item['price']))
                                        <p class="text-xs text-gray-400 mt-1">฿{{ number_format($item['price']) }}</p>
                                    @endif
                                    @if(isset($item['rating']))
                                        <p class="text-xs text-yellow-400 mt-1">{{ str_repeat('⭐', $item['rating']) }}</p>
                                    @endif
                                    @if(isset($item['booking_status']))
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 mt-1 inline-block">
                                            {{ $item['booking_status'] }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-white/10 h-40 flex items-center justify-center">
                            <p class="text-gray-500 text-sm">ไม่มีข้อมูล</p>
                        </div>
                    @endif
                </div>
                {{-- Raw --}}
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs text-gray-500 mb-2 font-mono">Raw JSON Response</p>
                    <pre class="text-xs text-gray-300 overflow-auto max-h-72 leading-relaxed">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-white/10 h-40 flex items-center justify-center">
                <span wire:loading>⏳ Loading...</span>
                <span wire:loading.remove class="text-gray-500 text-sm">เลือก tab เพื่อโหลดข้อมูล</span>
            </div>
        @endif

        </div>
    @endif
</div>
