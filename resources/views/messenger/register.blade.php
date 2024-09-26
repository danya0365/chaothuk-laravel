<x-guest-layout>
    <form method="POST" action="{{ route('messenger.mobile-phone-channel.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="mobile_phone" :value="__('เบอร์มือถือ')" />
            <x-text-input id="mobile_phone" class="block mt-1 w-full" type="text" name="mobile_phone" :value="old('mobile_phone')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('mobile_phone')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('พูดคุยกับเจ้าหน้าที่') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
