<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo + Title --}}
        <div class="text-center mb-8">
            <a href="{{ route('frontend.home') }}" class="inline-block text-4xl font-black text-orange-400 mb-2">Chaothuk</a>
            <h1 class="text-2xl font-bold text-white">สมัครสมาชิก</h1>
            <p class="text-gray-500 text-sm mt-1">สร้างบัญชีใหม่เพื่อเริ่มใช้งาน Chaothuk</p>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 shadow-xl">

            <form wire:submit="register" class="space-y-5">

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">ชื่อ</label>
                    <input type="text" wire:model="name" placeholder="ชื่อ-นามสกุล" autofocus
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 text-sm
                                  focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">อีเมล</label>
                    <input type="email" wire:model="email" placeholder="you@example.com"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 text-sm
                                  focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">รหัสผ่าน</label>
                    <input type="password" wire:model="password" placeholder="อย่างน้อย 8 ตัวอักษร"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 text-sm
                                  focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">ยืนยันรหัสผ่าน</label>
                    <input type="password" wire:model="password_confirmation" placeholder="กรอกรหัสผ่านอีกครั้ง"
                           class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 text-sm
                                  focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500/30 transition">
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500
                               text-white font-bold rounded-xl transition shadow-lg shadow-orange-500/20 text-sm"
                        wire:loading.class="opacity-50 cursor-wait" wire:loading.attr="disabled">
                    <span wire:loading.remove>สมัครสมาชิก</span>
                    <span wire:loading>กำลังสมัคร...</span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-800"></div>
                <span class="text-xs text-gray-600">หรือ</span>
                <div class="flex-1 h-px bg-gray-800"></div>
            </div>

            {{-- Login link --}}
            <p class="text-center text-sm text-gray-400">
                มีบัญชีอยู่แล้ว?
                <a href="{{ route('frontend.auth.login') }}" wire:navigate
                   class="text-orange-400 hover:text-orange-300 font-semibold transition">เข้าสู่ระบบ</a>
            </p>
        </div>

    </div>
</div>
