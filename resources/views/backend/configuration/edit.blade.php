@php
    $yesNoSelections = [['id' => 1, 'label' => 'ไช่', 'value' => 1], ['id' => 2, 'label' => 'ไม่', 'value' => 0]];
    $optionSelections = [];
    if ($configuration->value_options) {
        foreach ($configuration->value_options as $key => $valueOption) {
            $optionSelections[] = ['id' => $key, 'label' => $valueOption, 'value' => $valueOption];
        }
    }
@endphp
<x-backend-layout>
    <x-slot name="header">
        @include('backend.configuration.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <h1 class="text-3xl font-bold">
                    Update
                </h1>
                <p class="my-1 max-w-2xl text-sm leading-6 text-red-500 font-bold">**ต้องกรอกข้อมูลให้ครบถ้วน**</p>
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.configurations.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="flex flex-col mt-5">
                <div class="flex flex-col">
                    <div
                        class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                        @if ($errors->any())
                            <div class="p-4 rounded bg-red-500 text-red-100 mb-4">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div
                            class="w-full px-6 py-4 bg-white dark:bg-gray-800 rounded shadow-md ring-1 ring-gray-900/10">

                            <form action="{{ route('backend.configurations.update', $configuration->id) }}"
                                method="POST">
                                {{ method_field('PATCH') }}
                                @csrf

                                <div class="flex flex-col space-y-4" x-data="{ 'value_type': '{!! $configuration->value_type !!}' }">
                                    <div class="hidden">
                                        <x-input-label for="slug" :value="__('ตัวแปร')" />
                                        <x-text-input id="slug" name="slug" :value="$configuration->slug" type="text"
                                            class="mt-1 block w-full" required />
                                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="name" :value="__('ชื่อการตั้งค่า')" />
                                        <x-text-input id="name" name="name" :value="$configuration->name" type="text"
                                            class="mt-1 block w-full" required />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>

                                    <div class="hidden">
                                        <x-input-label for="value_type" :value="__('ประเภทตัวแปร')" />
                                        <x-radio-input name="value_type" class="mt-1 block w-full" required
                                            :selections="$valueTypeSelections" :selected="$configuration->value_type" />
                                        <x-input-error :messages="$errors->get('value_type')" class="mt-2" />
                                    </div>

                                    <template x-if="value_type == 'text'">
                                        <div>
                                            <x-input-label for="value" :value="__('ระบุการตั้งค่า')" />
                                            <x-text-input id="value" name="value" :value="$configuration->value"
                                                type="text" class="mt-1 block w-full" required />
                                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                                        </div>
                                    </template>
                                    <template x-if="value_type == 'textarea'">
                                        <div>
                                            <x-input-label for="value" :value="__('ระบุการตั้งค่า')" />
                                            <x-textarea id="value" name="value" type="text"
                                                class="mt-1 block w-full">{{ $configuration->value }}</x-textarea>
                                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                                        </div>
                                    </template>
                                    <template x-if="value_type == 'boolean'">
                                        <div>
                                            <x-input-label for="value" :value="__('เลือกการตั้งค่า')" />
                                            <x-radio-input name="value" class="mt-1 block w-full" required
                                                :selections="$yesNoSelections" :selected="$configuration->value ? 1 : 0" />
                                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                                        </div>
                                    </template>
                                    <template x-if="value_type == 'option'">
                                        <div>
                                            <x-input-label for="value" :value="__('เลือกการตั้งค่า')" />
                                            <x-radio-input name="value" class="mt-1 block w-full" required
                                                :selections="$optionSelections" :selected="$configuration->value" />
                                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                                        </div>
                                    </template>

                                    <div class="flex items-center justify-start mt-4 gap-x-2">
                                        <button type="submit"
                                            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
                                    </div>
                                </div>


                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-backend-layout>
