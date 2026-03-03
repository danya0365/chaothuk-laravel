<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo + Title --}}
        <div class="text-center mb-8">
            <a href="{{ route('frontend.home') }}" class="inline-block text-4xl font-black text-orange-400 mb-2">Chaothuk</a>
            <h1 class="text-2xl font-bold text-white">ลืมรหัสผ่าน</h1>
            <p class="text-gray-500 text-sm mt-1">กรอกอีเมลของคุณ เราจะส่งลิงก์รีเซ็ตให้</p>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 shadow-xl">

            {{-- Success message --}}
            @if($successMessage)
                <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl px-4 py-3 text-sm mb-5">
                    ✅ {{ $successMessage }}
                </div>
            @endif

            <form wire:submit="sendResetLink" class="space-y-5">

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

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500
                               text-white font-bold rounded-xl transition shadow-lg shadow-orange-500/20 text-sm"
                        wire:loading.class="opacity-50 cursor-wait" wire:loading.attr="disabled">
                    <span wire:loading.remove>ส่งลิงก์รีเซ็ต</span>
                    <span wire:loading>กำลังส่ง...</span>
                </button>
            </form>

            {{-- Back to login --}}
            <div class="mt-6 text-center">
                <a href="{{ route('frontend.auth.login') }}" wire:navigate
                   class="text-sm text-gray-400 hover:text-orange-400 transition">
                    ← กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </div>

    </div>
</div>
