<div class="w-full">
    <!-- Header -->
    <div class="text-center mb-10">
        <x-application-logo class="w-16 h-16 mx-auto text-indigo-600 dark:text-indigo-400 mb-4" />
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Admin Gateway</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            ระบบจัดการและควบคุมส่วนกลาง <br /> (Chaothuk Management System)
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                อีเมลผู้ดูแลระบบ (Admin Email)
            </label>
            <div class="mt-2 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <input wire:model="email" id="email" type="email" name="email" required autofocus autocomplete="username"
                       class="block w-full pl-10 pr-3 py-2.5 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition duration-150 ease-in-out" 
                       placeholder="admin@chaothuk.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                รหัสผ่าน (Password)
            </label>
            <div class="mt-2 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model="password" id="password" type="password" name="password" required autocomplete="current-password"
                       class="block w-full pl-10 pr-3 py-2.5 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 transition duration-150 ease-in-out" 
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input wire:model="remember" id="remember" name="remember" type="checkbox" 
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    จดจำการเข้าระบบ
                </label>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition duration-150 ease-in-out">
                        ลืมรหัสผ่าน?
                    </a>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            {{-- Demo Users Helper (Only visible outside production) --}}
            @if(config('app.env') !== 'production')
            <div class="mb-6 p-4 bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-200 dark:border-indigo-800/30 rounded-lg">
                <p class="text-xs text-indigo-700 dark:text-indigo-400 font-bold mb-3 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span>บัญชีทดสอบสำหรับแอดมิน (คลิกเพื่อเติมอัตโนมัติ)</span>
                </p>
                <div class="grid grid-cols-1 gap-2">
                    <button type="button" @click="$wire.email = '{{ config('auth.supervisor.email') }}'; $wire.password = '{{ config('auth.supervisor.password') }}'"
                            class="py-2 px-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-700 text-left transition group shadow-sm flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-gray-500 font-medium group-hover:text-indigo-600 dark:group-hover:text-indigo-400">🛡️ Supervisor Admin</div>
                            <div class="text-xs text-gray-900 dark:text-gray-300 truncate">{{ config('auth.supervisor.email') }}</div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition duration-150 ease-in-out">
                <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="login">เข้าสู่ระบบ (Secure Area)</span>
                <span wire:loading wire:target="login">กำลังประมวลผล...</span>
            </button>
            <p class="mt-4 text-xs text-center text-gray-500 dark:text-gray-400">
                ระบบนี้สงวนสิทธิ์เฉพาะทีมงานแอดมินเท่านั้น การเข้าถึงโดยไม่ได้รับอนุญาตถือเป็นความผิด
            </p>
        </div>
    </form>
</div>
