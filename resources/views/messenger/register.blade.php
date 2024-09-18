<x-guest-layout>
    <form method="POST" action="{{ route('messenger.telephone-channel.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="telephone" :value="__('Telephone')" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('พูดคุยกับเจ้าหน้าที่') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
