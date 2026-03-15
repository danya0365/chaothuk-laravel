<div>
    <div class="max-w-md mx-auto py-12 px-6">
        <form method="POST" action="{{ route('messenger.mobile-phone-channel.register') }}" class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            @csrf

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 text-center">ยืนยันเบอร์มือถือ</h2>

            <!-- Name -->
            <div>
                <x-input-label for="mobile_phone" :value="__('เบอร์มือถือ')" />
                <x-text-input id="mobile_phone" class="block mt-1 w-full" type="text" name="mobile_phone" :value="old('mobile_phone')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('mobile_phone')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button class="w-full justify-center">
                    {{ __('พูดคุยกับเจ้าหน้าที่') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
