<div class="flex flex-col space-y-4">

    <div>
        <x-input-label for="code" :value="__('ทะเบียนรถ')" />
        <x-text-input :value="old('code', $work?->code)" id="code" name="code" type="text" class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="title" :value="__('ชื่อ')" />
        <x-text-input :value="old('title', $work?->title)" id="title" name="title" type="text" class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('รายละเอียด')" />
        <x-textarea :value="old('description', $work?->description)" id="description" name="description"
            class="mt-1 block w-full">{{ old('description', $work?->description) }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="details" :value="__('เนื้อหา (ใส่ , เพื่อแยกข้อความ)')" />
        <x-textarea id="details" name="details" class="mt-1 block w-full">{!! old('details', $work?->details ? implode(',', $work?->details) : null) !!}</x-textarea>
        <x-input-error :messages="$errors->get('details')" class="mt-2" />
    </div>

    <div x-data="alpineUploadImage('{{ old('primary_image', $work?->primary_image) }}')">
        <x-input-label for="primary_image" :value="__('รูปหลัก')" />
        <x-text-input x-model="uploadFile" :value="old('primary_image', $work?->primary_image)" id="primary_image" placeholder="URL รูป"
            name="primary_image" type="text" class="mt-1 block w-full" />
        <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
            for="primary_image_upload_file">
            <input type="file" class="sr-only" id="primary_image_upload_file"
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

    <div x-data="alpineGalleryFormData()">
        <x-input-label for="images" :value="__('รูปแกลเลอรี่ เป็นต้น (สูงสุด 10 ไฟล์)')" />

        <div class="relative overflow-x-auto border-y border-gray-200 dark:border-gray-600">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-3 text-center">
                            {{ __('ลำดับที่') }}
                        </th>
                        <th scope="col" class="p-3">
                            {{ __('ที่อยู่รูปภาพ') }}
                        </th>
                        <th scope="col" class="p-3">
                            {{ __('ดาว์นโหลด') }}
                        </th>
                        <th scope="col" class="p-3">
                            <span class="sr-only">{{ __('ลบออก') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="galleryFields.length > 0">
                        <template x-for="(field, index) in galleryFields" :key="index">
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="p-2 text-center">
                                    <span x-text="index+1" />
                                </th>
                                <td scope="row" class="p-2">
                                    <x-text-input x-model="field.url" x-bind:name="`images[]`" class="block w-full"
                                        required />
                                </td>
                                <td scope="row" class="p-2">
                                    <a x-bind:href="field.url" target="_blank" class="block w-full">Download</a>
                                </td>
                                <td class="p-2 text-right">
                                    <button type="button" x-on:click="removeField(index)"
                                        class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-red-500 hover:bg-red-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">-</button>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template x-if="galleryFields.length === 0">
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" colspan="4" class="p-2 text-center">
                                {{ __('Empty') }}
                            </th>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="p-2">

                            <form class="max-w-lg mx-auto space-y-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    for="gallery_upload_file">อัพโหลดไฟล์รูป</label>
                                <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
                                    for="gallery_upload_file">
                                    <input type="file" class="sr-only" id="gallery_upload_file" multiple
                                        x-on:change="files = Object.values($event.target.files)">
                                    <span
                                        x-text="files ? files.map(file => file.name).join(', ') : 'คลิกเพื่อเลือกไฟล์'"></span>
                                </label>
                                <div class="flex items-center justify-start mt-4 gap-x-2">
                                    <button x-on:click="submitUpload()" type="button"
                                        class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">{{ __('Upload') }}</button>
                                    <button x-on:click="resetUpload()" type="button"
                                        class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-red-500 hover:bg-red-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">{{ __('Reset') }}</button>
                                </div>
                            </form>

                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div>
        <x-input-label for="price" :value="__('ราคา')" />
        <x-text-input :value="old('price', $work?->price)" id="price" name="price" type="text" class="mt-1 block w-full"
            required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="display_priority" :value="__('การเรียงลำดับ')" />
        <x-text-input :value="old('display_priority', $work?->display_priority ?? 1)" id="display_priority" name="display_priority" type="text"
            class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('display_priority')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="province_id" :value="__('จังหวัด')" />
        <x-radio-input name="province_id" class="mt-1 block w-full" required :selections="$provinceSelections" :selected="old('province_id', $work?->province_id)" />
        <x-input-error :messages="$errors->get('province_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="work_type_id" :value="__('ประเภทรถ')" />
        <x-radio-input name="work_type_id" class="mt-1 block w-full" required :selections="$workTypeSelections" :selected="old('work_type_id', $work?->work_type_id)" />
        <x-input-error :messages="$errors->get('work_type_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="author_id" :value="__('เจ้าของงาน')" />
        <x-radio-input name="author_id" class="mt-1 block w-full" required :selections="$userSelections" :selected="old('author_id', $work?->author_id)" />
        <x-input-error :messages="$errors->get('author_id')" class="mt-2" />
    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>
</div>
<x-slot name="javascript">
    <script type="text/javascript">
        function alpineGalleryFormData() {
            const initGalleryFields = [];
            @foreach ($work?->images as $galleryImage)
                initGalleryFields.push({
                    url: '{{ $galleryImage }}',
                })
            @endforeach
            return {
                galleryFields: initGalleryFields,
                async submitUpload() {
                    if (this.files === null) return;

                    let formData = new FormData();
                    formData.append('image', this.files[0]);
                    const uploadResponse = await $.ajax({
                        url: '{{ route('ajax.upload.image') }}',
                        type: 'POST',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                    });

                    if (!uploadResponse.data) {
                        return;
                    }

                    const documentUploadUrl = uploadResponse.data.original

                    this.galleryFields.push({
                        url: documentUploadUrl
                    });
                    this.files = null;
                },
                resetUpload() {
                    this.files = null;
                },
                removeField(index) {
                    this.galleryFields.splice(index, 1);
                },
                files: null
            }
        }
    </script>
</x-slot>
