<x-guest-layout>
    <form method="POST" action="{{ route('messenger.mobilephone-channel.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="mobilePhone" :value="__('mobilePhone')" />
            <x-text-input id="mobilePhone" class="block mt-1 w-full" type="text" name="mobilePhone" :value="old('mobilePhone')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('mobilePhone')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('พูดคุยกับเจ้าหน้าที่') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
