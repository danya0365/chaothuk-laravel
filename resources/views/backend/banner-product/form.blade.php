<div class="flex flex-col space-y-4">

    <x-text-input id="merchant_id" name="merchant_id" :value="old('merchant_id', $bannerProduct?->merchant_id ?? $merchantId)" type="hidden" class="mt-1 block w-full" required />

    <div>
        <x-input-label for="code" :value="__('รหัส')" />
        <x-text-input id="code" name="code" :value="old('code', $bannerProduct?->code)" type="text"
            class="mt-1 block w-full @error('code')
is-invalid
@enderror" readonly
            placeholder="ระบบจะสุ่มเลขอัตโนมัติ" />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="name" :value="__('ชื่อ')" />
        <x-text-input id="name" name="name" :value="old('name', $bannerProduct?->name)" type="text"
            class="mt-1 block w-full @error('name')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="detail" :value="__('เนื้อหา')" />
        <x-textarea id="detail" name="detail" class="mt-1 block w-full"
            required>{!! old('detail', $bannerProduct?->detail) !!}</x-textarea>
        <x-input-error :messages="$errors->get('detail')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="condition_text" :value="__('เงื่อนไข')" />
        <x-textarea id="condition_text" name="condition_text" class="mt-1 block w-full"
            required>{!! old('condition_text', $bannerProduct?->condition_text) !!}</x-textarea>
        <x-input-error :messages="$errors->get('condition_text')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="expired_at" :value="__('วันที่หมดอายุ')" />
        <x-text-input id="expired_at" name="expired_at" :value="old('expired_at', $bannerProduct?->getExpiredDate())" type="text"
            class="mt-1 block w-full date" />
        <x-input-error :messages="$errors->get('expired_at')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="tags" :value="__('แท็ก')" />
        <x-text-input id="tags" name="tags" :value="old('tags', $bannerProduct?->textTags())" type="text"
            class="mt-1 block w-full @error('tags')
is-invalid
@enderror" />
        <x-input-error :messages="$errors->get('tags')" class="mt-2" />
    </div>
    <div x-data="alpineUploadImage('{{ old('image_url', $bannerProduct?->image_url) }}')">
        <x-input-label for="image_url" :value="__('รูป')" />
        <x-text-input x-model="uploadFile" :value="old('image_url', $bannerProduct?->image_url)" id="image_url" placeholder="URL รูป" name="image_url"
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
        <x-input-label for="redeem_points" :value="__('แต้มที่ใช้แลก')" />
        <x-text-input id="redeem_points" name="redeem_points" :value="old('redeem_points', $bannerProduct?->redeem_points)" type="text"
            class="mt-1 block w-full @error('redeem_points')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('redeem_points')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="available_redeems" :value="__('จำนวนคงเหลือที่แลกได้  (ใส่ -1 ไม่จำกัด)')" />
        <x-text-input id="available_redeems" name="available_redeems" :value="old('available_redeems', $bannerProduct?->available_redeems)" type="text"
            class="mt-1 block w-full @error('available_redeems')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('available_redeems')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="max_redeem_per_user" :value="__('จำนวนที่แลกได้ต่อ 1 user (ใส่ -1 ไม่จำกัด)')" />
        <x-text-input id="max_redeem_per_user" name="max_redeem_per_user" :value="old('max_redeem_per_user', $bannerProduct?->max_redeem_per_user)" type="text"
            class="mt-1 block w-full @error('max_redeem_per_user')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('max_redeem_per_user')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="coupon_expires_type" :value="__('ประเภทอายุคูปอง')" />
        <x-radio-input name="coupon_expires_type" class="mt-1 block w-full" required :selections="$couponExpiresTypeSelections"
            :selected="old('coupon_expires_type', $bannerProduct?->coupon_expires_type)" />
        <x-input-error :messages="$errors->get('coupon_expires_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label :value="__('ประเภท User ที่มีสิทธิ')" />
        <x-select-multiple name="user_types" class="mt-1 block w-full" :selections="$userTypeSelections" :selected="old('user_types', $bannerProduct?->getUserTypesValue())" />
        <x-input-error :messages="$errors->get('user_types')" class="mt-2" />
    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>

</div>
