<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo + Title --}}
        <div class="text-center mb-8">
            <a href="{{ route('frontend.home') }}" class="inline-block text-4xl font-black text-orange-400 mb-2">Chaothuk</a>
            <h1 class="text-2xl font-bold text-white">เข้าสู่ระบบ</h1>
            <p class="text-gray-500 text-sm mt-1">ยินดีต้อนรับกลับ เข้าสู่ระบบเพื่อใช้งาน</p>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 shadow-xl">

            <form wire:submit="login" class="space-y-5">

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">อีเมล</label>
                    <input type="email" wire:model="email" placeholder="you@example.com" autofocus
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 text-sm
                                  focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-medium text-gray-300">รหัสผ่าน</label>
                        <a href="{{ route('frontend.auth.forgot-password') }}" wire:navigate
                           class="text-xs text-orange-400 hover:text-orange-300 transition">ลืมรหัสผ่าน?</a>
                    </div>
                    <input type="password" wire:model="password" placeholder="••••••••"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 text-sm
                                  focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="remember"
                           class="rounded bg-gray-800 border-gray-600 text-orange-500 focus:ring-orange-500/50 w-4 h-4">
                    <span class="text-sm text-gray-400">จดจำฉันไว้</span>
                </label>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500
                               text-white font-bold rounded-xl transition shadow-lg shadow-orange-500/20 text-sm"
                        wire:loading.class="opacity-50 cursor-wait" wire:loading.attr="disabled">
                    <span wire:loading.remove>เข้าสู่ระบบ</span>
                    <span wire:loading>กำลังเข้าสู่ระบบ...</span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-800"></div>
                <span class="text-xs text-gray-600">หรือ</span>
                <div class="flex-1 h-px bg-gray-800"></div>
            </div>

            {{-- Register link --}}
            <p class="text-center text-sm text-gray-400">
                ยังไม่มีบัญชี?
                <a href="{{ route('frontend.auth.register') }}" wire:navigate
                   class="text-orange-400 hover:text-orange-300 font-semibold transition">สมัครสมาชิก</a>
            </p>
        </div>

    </div>
</div>
