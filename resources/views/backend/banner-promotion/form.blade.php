<div class="flex flex-col space-y-4" x-data="alpineFormData('{{ old('type', $bannerPromotion?->type) }}')">

    <x-text-input id="merchant_id" name="merchant_id" :value="old('merchant_id', $bannerPromotion?->merchant_id ?? $merchantId)" type="hidden" class="mt-1 block w-full" required />

    <div>
        <x-input-label for="type" :value="__('ประเภทโปรโมชั่น')" />
        <x-radio-input x-model="type" name="type" class="mt-1 block w-full" required :selections="$promotionTypeSelections"
            :selected="$bannerPromotion?->type" />
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="code" :value="__('รหัส')" />
        <x-text-input id="code" name="code" :value="old('code', $bannerPromotion?->code)" type="text"
            class="mt-1 block w-full @error('code')
is-invalid
@enderror" readonly
            placeholder="ระบบจะสุ่มเลขอัตโนมัติ" />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="name" :value="__('ชื่อ')" />
        <x-text-input id="name" name="name" :value="old('name', $bannerPromotion?->name)" type="text"
            class="mt-1 block w-full @error('name')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="detail" :value="__('เนื้อหา')" />
        <x-textarea id="detail" name="detail" class="mt-1 block w-full"
            required>{!! old('detail', $bannerPromotion?->detail) !!}</x-textarea>
        <x-input-error :messages="$errors->get('detail')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tags" :value="__('แท็ก')" />
        <x-text-input id="tags" name="tags" :value="old('tags', $bannerPromotion?->textTags())" type="text"
            class="mt-1 block w-full @error('tags')
is-invalid
@enderror" />
        <x-input-error :messages="$errors->get('tags')" class="mt-2" />
    </div>
    <div x-data="alpineUploadImage('{{ old('image_url', $bannerPromotion?->image_url) }}')">
        <x-input-label for="image_url" :value="__('รูป')" />
        <x-text-input x-model="uploadFile" :value="old('image_url', $bannerPromotion?->image_url)" id="image_url" placeholder="URL รูป" name="image_url"
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

    <template x-if="isMission()">
        <div class="flex flex-col space-y-4">
            <h2>ข้อมูลสำหรับภารกิจ</h2>
            <div>
                <x-input-label for="condition_text" :value="__('เงื่อนไข')" />
                <x-textarea id="condition_text" name="condition_text" type="text" class="mt-1 block w-full"
                    required>{!! old('condition_text', $bannerPromotion?->condition_text) !!}</x-textarea>
                <x-input-error :messages="$errors->get('condition_text')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="acquire_points" :value="__('แต้มที่ได้')" />
                <x-text-input id="acquire_points" name="acquire_points" :value="old('acquire_points', $bannerPromotion?->acquire_points)" type="text"
                    class="mt-1 block w-full @error('acquire_points')
is-invalid
@enderror" required />
                <x-input-error :messages="$errors->get('acquire_points')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="available_missions" :value="__('จำนวนคงเหลือที่ทำภารกิจได้  (ใส่ -1 ไม่จำกัด)')" />
                <x-text-input id="available_missions" name="available_missions" :value="old('available_missions', $bannerPromotion?->available_missions)" type="text"
                    class="mt-1 block w-full @error('available_missions')
is-invalid
@enderror" required />
                <x-input-error :messages="$errors->get('available_missions')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="max_mission_per_user" :value="__('จำนวนที่ทำภารกิจได้ต่อ 1 user  (ใส่ -1 ไม่จำกัด)')" />
                <x-text-input id="max_mission_per_user" name="max_mission_per_user" :value="old('max_mission_per_user', $bannerPromotion?->max_mission_per_user)" type="text"
                    class="mt-1 block w-full @error('max_mission_per_user')
is-invalid
@enderror" required />
                <x-input-error :messages="$errors->get('max_mission_per_user')" class="mt-2" />
            </div>

        </div>
    </template>

    <div>
        <x-input-label for="expired_at" :value="__('วันที่หมดอายุ')" />
        <x-text-input id="expired_at" name="expired_at" :value="old('expired_at', $bannerPromotion?->getExpiredDate())" type="text"
            class="mt-1 block w-full date" />
        <x-input-error :messages="$errors->get('expired_at')" class="mt-2" />
    </div>

    <div>
        <x-input-label :value="__('ประเภท User ที่มีสิทธิ')" />
        <x-select-multiple name="user_types" class="mt-1 block w-full" :selections="$userTypeSelections" :selected="old('user_types', $bannerPromotion?->getUserTypesValue())" />
        <x-input-error :messages="$errors->get('user_types')" class="mt-2" />
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
                isMission() {
                    return this.type === 'mission'
                },
            }
        }
    </script>
</x-slot>
