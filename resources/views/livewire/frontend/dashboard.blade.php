<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- Page Header --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-white">🏠 Dashboard</h1>
        <p class="mt-2 text-gray-400">จัดการ Sanctum API Token เพื่อเรียกใช้ API endpoints</p>
    </div>

    {{-- Token Status Card --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Login / Token Section --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-lg font-semibold text-white mb-1">🔑 API Token</h2>
            <p class="text-sm text-gray-400 mb-5">Login เพื่อรับ Sanctum Bearer Token</p>

            @if($apiToken)
                {{-- Active Token --}}
                <div class="mb-5 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="text-sm font-medium text-emerald-400">Token Active</span>
                    </div>
                    <div class="font-mono text-xs text-gray-300 break-all leading-relaxed bg-black/30 rounded-lg p-3 select-all">
                        {{ $apiToken }}
                    </div>
                </div>

                @if($apiResponse)
                    <div class="mb-4 p-3 rounded-xl bg-white/5 border border-white/10">
                        <p class="text-xs text-gray-500 mb-1">API Response</p>
                        <pre class="text-xs text-gray-300 overflow-auto max-h-24">{{ json_encode($apiResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif

                <button wire:click="logout"
                        class="w-full py-2.5 rounded-xl font-medium text-sm bg-rose-500/10 border border-rose-500/30 text-rose-400 hover:bg-rose-500/20 transition-all">
                    🔌 Clear Token / Logout API
                </button>
            @else
                {{-- Login Form --}}
                @if($errorMessage)
                    <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-sm text-rose-400">
                        ⚠️ {{ $errorMessage }}
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Email</label>
                        <input wire:model="email" type="email"
                               placeholder="worker1@chaothuk.test"
                               class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-600 focus:outline-none focus:border-orange-500/50 text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Password</label>
                        <input wire:model="password" type="password"
                               placeholder="password"
                               class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-600 focus:outline-none focus:border-orange-500/50 text-sm transition-colors">
                    </div>
                    <button wire:click="login"
                            wire:loading.attr="disabled"
                            class="w-full py-2.5 rounded-xl font-semibold text-sm bg-gradient-to-r from-orange-500 to-rose-600 text-white hover:opacity-90 transition-all disabled:opacity-50">
                        <span wire:loading.remove>🔓 Login & Get Token</span>
                        <span wire:loading>⏳ Logging in...</span>
                    </button>
                </div>

                <div class="mt-4 p-3 rounded-xl bg-blue-500/10 border border-blue-500/20">
                    <p class="text-xs text-blue-400 font-medium mb-1">Demo Accounts</p>
                    <div class="space-y-0.5 text-xs text-gray-400 font-mono">
                        <p>worker1@chaothuk.test / password</p>
                        <p>employer1@chaothuk.test / password</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- API Health / Quick Info --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h2 class="text-lg font-semibold text-white mb-1">📡 API Endpoints</h2>
            <p class="text-sm text-gray-400 mb-5">Routes ที่มีอยู่ในระบบ</p>

            <div class="space-y-2">
                @foreach([
                    ['tag' => 'GET',  'color' => 'emerald', 'path' => '/api/works',                  'desc' => 'List works'],
                    ['tag' => 'GET',  'color' => 'emerald', 'path' => '/api/works/{id}',              'desc' => 'Work detail'],
                    ['tag' => 'POST', 'color' => 'blue',    'path' => '/api/works/{id}/reviews',      'desc' => 'Submit review 🔒'],
                    ['tag' => 'GET',  'color' => 'emerald', 'path' => '/api/recruits',               'desc' => 'List recruits'],
                    ['tag' => 'GET',  'color' => 'emerald', 'path' => '/api/recruits/{id}',           'desc' => 'Recruit detail'],
                    ['tag' => 'POST', 'color' => 'blue',    'path' => '/api/recruits/{id}/reviews',   'desc' => 'Submit review 🔒'],
                    ['tag' => 'GET',  'color' => 'emerald', 'path' => '/api/me',                     'desc' => 'My profile 🔒'],
                    ['tag' => 'GET',  'color' => 'emerald', 'path' => '/api/me/works',               'desc' => 'My works 🔒'],
                    ['tag' => 'POST', 'color' => 'orange',  'path' => '/api/auth/login',             'desc' => 'Get Sanctum token'],
                ] as $ep)
                    <div class="flex items-center gap-3 py-2 px-3 rounded-lg bg-white/3 hover:bg-white/6 transition-colors">
                        <span class="shrink-0 px-2 py-0.5 rounded text-xs font-bold font-mono
                            {{ $ep['color'] === 'emerald' ? 'bg-emerald-500/20 text-emerald-400' :
                               ($ep['color'] === 'blue' ? 'bg-blue-500/20 text-blue-400' : 'bg-orange-500/20 text-orange-400') }}">
                            {{ $ep['tag'] }}
                        </span>
                        <span class="text-xs font-mono text-gray-300 flex-1">{{ $ep['path'] }}</span>
                        <span class="text-xs text-gray-500">{{ $ep['desc'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Navigation --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
            ['href' => route('frontend.works'),    'icon' => '💼', 'label' => 'Works Explorer',    'desc' => 'Browse & review works'],
            ['href' => route('frontend.recruits'), 'icon' => '👤', 'label' => 'Recruit Explorer',  'desc' => 'Browse & review recruits'],
            ['href' => route('frontend.me'),       'icon' => '🔮', 'label' => 'Me Explorer',       'desc' => 'View your profile & data'],
        ] as $item)
            <a href="{{ $item['href'] }}"
               class="group flex items-center gap-4 p-5 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-orange-500/30 transition-all">
                <span class="text-3xl">{{ $item['icon'] }}</span>
                <div>
                    <p class="font-semibold text-white text-sm group-hover:text-orange-400 transition-colors">{{ $item['label'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $item['desc'] }}</p>
                </div>
                <svg class="ml-auto w-4 h-4 text-gray-600 group-hover:text-orange-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @endforeach
    </div>

</div>
