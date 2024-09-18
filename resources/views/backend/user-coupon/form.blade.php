<div class="flex flex-col space-y-4">

    <x-text-input id="user_id" name="user_id" :value="old('user_id', $userCoupon?->customer_id ?? $customerId)" type="hidden" class="mt-1 block w-full" required />
    <x-text-input id="banner_product_id" name="banner_product_id" :value="old('banner_product_id', $userCoupon?->banner_product_id ?? $bannerProductId)" type="hidden" class="mt-1 block w-full"
        required />

    <div>
        <x-input-label for="code" :value="__('รหัส')" />
        <x-text-input id="code" name="code" :value="old('code', $userCoupon?->code)" type="text"
            class="mt-1 block w-full @error('code')
is-invalid
@enderror" readonly
            placeholder="ระบบจะสุ่มเลขอัตโนมัติ" />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="coupon_received" :value="__('จำนวนคูปองที่ได้รับทั้งหมด')" />
        <x-text-input id="coupon_received" name="coupon_received" :value="old('coupon_received', $userCoupon?->coupon_received ?? 1)" type="text"
            class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('coupon_received')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="coupon_available" :value="__('จำนวนคูปองคงเหลือที่ใช้ได้')" />
        <x-text-input id="coupon_available" name="coupon_available" :value="old('coupon_available', $userCoupon?->coupon_available ?? 1)" type="text"
            class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('coupon_available')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="expired_at" :value="__('วันที่หมดอายุ')" />
        <x-text-input id="expired_at" name="expired_at" :value="old('expired_at', $userCoupon?->getExpiredDate())" type="text" class="mt-1 block w-full date"
            required />
        <x-input-error :messages="$errors->get('expired_at')" class="mt-2" />
    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>

</div>
