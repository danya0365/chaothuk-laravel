<div class="flex flex-col space-y-4" x-data="alpineFormData('{{ old('type', $banner?->type) }}')">

    <div>
        <x-input-label for="type" :value="__('ประเภทแบนเนอร์')" />
        <x-radio-input x-model="type" name="type" class="mt-1 block w-full" required :selections="$bannerTypeSelections"
            :selected="$banner?->type" />
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <template x-if="isExternalUrl()">
        <div class="flex flex-col space-y-4">
            <h2>ข้อมูลสำหรับ External Url</h2>

            <div>
                <x-input-label for="external_url" :value="__('External Url')" />
                <x-text-input id="external_url" name="external_url" :value="old('external_url', $banner?->external_url)" type="text"
                    class="mt-1 block w-full @error('external_url')
is-invalid
@enderror" />
                <x-input-error :messages="$errors->get('external_url')" class="mt-2" />
            </div>

        </div>
    </template>

    <template x-if="isProduct()">
        <div class="flex flex-col space-y-4">
            <h2>ข้อมูลสำหรับ Product</h2>

            <div>
                <x-input-label for="banner_product_id" :value="__('เลือก product')" />
                <x-radio-input name="banner_product_id" class="mt-1 block w-full" required :selections="$bannerProductSelections"
                    :selected="old('banner_product_id', $banner?->banner_product_id)" />
                <x-input-error :messages="$errors->get('banner_product_id')" class="mt-2" />
            </div>

        </div>
    </template>

    <template x-if="isPromotion()">
        <div class="flex flex-col space-y-4">
            <h2>ข้อมูลสำหรับ Promotion</h2>

            <div>
                <x-input-label for="banner_promotion_id" :value="__('เลือก promotion')" />
                <x-radio-input name="banner_promotion_id" class="mt-1 block w-full" required :selections="$bannerPromotionSelections"
                    :selected="old('banner_promotion_id', $banner?->banner_promotion_id)" />
                <x-input-error :messages="$errors->get('banner_promotion_id')" class="mt-2" />
            </div>

        </div>
    </template>

    <div>
        <x-input-label for="name" :value="__('ชื่อ')" />
        <x-text-input id="name" name="name" :value="old('name', $banner?->name)" type="text"
            class="mt-1 block w-full @error('name')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="view_count" :value="__('จำนวนเปิดอ่าน')" />
        <x-text-input id="view_count" name="view_count" :value="old('view_count', $banner?->view_count)" type="text"
            class="mt-1 block w-full @error('view_count')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('view_count')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="expired_at" :value="__('วันที่หมดอายุ')" />
        <x-text-input id="expired_at" name="expired_at" :value="old('expired_at', $banner?->expired_at)" type="text"
            class="mt-1 block w-full date" />
        <x-input-error :messages="$errors->get('expired_at')" class="mt-2" />
    </div>

    <div x-data="alpineUploadImage('{{ old('image_url', $banner?->image_url) }}')">
        <x-input-label for="image_url" :value="__('รูป')" />
        <x-text-input x-model="uploadFile" :value="old('image_url', $banner?->image_url)" id="image_url" placeholder="URL รูป" name="image_url"
            type="text" class="mt-1 block w-full" />
        <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
            for="banner_url_upload_file">
            <input type="file" class="sr-only" id="banner_url_upload_file"
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

    <div>
        <x-input-label for="is_public" :value="__('แสดง/ไม่แสดง')" />
        <x-radio-input name="is_public" class="mt-1 block w-full" required :selections="$publicSelections" :selected="old('is_public', $banner?->is_public)" />
        <x-input-error :messages="$errors->get('is_public')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="is_pinned" :value="__('ปักหมุดหน้าแรกของแอพ')" />
        <x-radio-input name="is_pinned" class="mt-1 block w-full" required :selections="$publicSelections" :selected="old('is_pinned', $banner?->is_pinned)" />
        <x-input-error :messages="$errors->get('is_pinned')" class="mt-2" />
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
                isExternalUrl() {
                    return this.type === '{{ \App\Enums\BannerType::EXTERNAL_URL->value }}'
                },
                isProduct() {
                    return this.type === '{{ \App\Enums\BannerType::PRODUCT->value }}'
                },
                isPromotion() {
                    return this.type === '{{ \App\Enums\BannerType::PROMOTION->value }}'
                },
            }
        }
    </script>
</x-slot>
