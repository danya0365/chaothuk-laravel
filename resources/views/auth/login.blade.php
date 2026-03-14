<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Demo Users Helper (Only visible outside production) --}}
    @if(config('app.env') !== 'production')
    <div class="mt-8 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg">
        <p class="text-xs text-indigo-800 dark:text-indigo-300 font-bold mb-3 flex items-center gap-1">
            <span>🧪</span> บัญชีทดสอบ (คลิกเพื่อเติมอัตโนมัติ)
        </p>
        <div class="grid grid-cols-2 gap-2">
            <button type="button" onclick="document.getElementById('email').value='worker1@chaothuk.test'; document.getElementById('password').value='password';"
                    class="py-2 px-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-gray-200 dark:border-gray-700 text-left transition group shadow-sm">
                <div class="text-[10px] text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">👨‍🔧 ผู้รับงาน 1</div>
                <div class="text-xs text-gray-700 dark:text-gray-300 truncate">worker1@chaothuk.test</div>
            </button>
            <button type="button" onclick="document.getElementById('email').value='worker2@chaothuk.test'; document.getElementById('password').value='password';"
                    class="py-2 px-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-gray-200 dark:border-gray-700 text-left transition group shadow-sm">
                <div class="text-[10px] text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">👨‍🔧 ผู้รับงาน 2</div>
                <div class="text-xs text-gray-700 dark:text-gray-300 truncate">worker2@chaothuk.test</div>
            </button>
            <button type="button" onclick="document.getElementById('email').value='employer1@chaothuk.test'; document.getElementById('password').value='password';"
                    class="py-2 px-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-gray-200 dark:border-gray-700 text-left transition group shadow-sm">
                <div class="text-[10px] text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">🏢 ผู้จ้าง 1</div>
                <div class="text-xs text-gray-700 dark:text-gray-300 truncate">employer1@chaothuk.test</div>
            </button>
            <button type="button" onclick="document.getElementById('email').value='admin@chaothuk.test'; document.getElementById('password').value='password';"
                    class="py-2 px-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded border border-gray-200 dark:border-gray-700 text-left transition group shadow-sm">
                <div class="text-[10px] text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">👑 แอดมิน</div>
                <div class="text-xs text-gray-700 dark:text-gray-300 truncate">admin@chaothuk.test</div>
            </button>
        </div>
    </div>
    @endif
</x-guest-layout>
