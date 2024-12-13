<div class="flex flex-col space-y-4" x-data="alpineFormData('{{ old('type', $category?->type) }}')">
    <div>
        <x-input-label for="name" :value="__('ชื่อ')" />
        <x-text-input id="name" name="name" :value="old('name', $category?->name)" type="text"
            class="mt-1 block w-full @error('name')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('รายละเอียด')" />
        <x-textarea :value="old('description', $category?->description)" id="description" name="description"
            class="mt-1 block w-full">{{ old('description', $category?->description) }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div x-data="alpineUploadImage('{{ old('image_url', $category?->image_url) }}')">
        <x-input-label for="image_url" :value="__('รูปหลัก')" />
        <x-text-input x-model="uploadFile" :value="old('image_url', $category?->image_url)" id="image_url" placeholder="URL รูป" name="image_url"
            type="text" class="mt-1 block w-full" />
        <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
            for="image_url_upload_file">
            <input type="file" class="sr-only" id="image_url_upload_file"
                x-on:change="files = Object.values($event.target.files)">
            <span x-text="files ? files.map(file => file.name).join(', ') : 'คลิกเพื่อเลือกไฟล์'"></span>
        </label>
        <div class="flex items-center justify-start mt-4 gap-x-2">
            <button x-on:click="submitUpload()" type="button"
                class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">{{ __('Upload') }}</button>
            <button x-on:click="resetUpload()" type="button"
                class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-red-500 hover:bg-red-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">{{ __('Reset') }}</button>
        </div>
    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>

</div>

<x-slot name="javascript">
    <script type="text/javascript">
        function alpineFormData(type) {
            return {
                type: type,
            }
        }
    </script>
</x-slot>
