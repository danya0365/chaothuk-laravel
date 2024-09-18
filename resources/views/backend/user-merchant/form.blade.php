<div class="flex flex-col space-y-4">
    <x-text-input type="hidden" name="user_id" :value="$user?->id" />

    <div>
        <x-input-label for="name" :value="__('ชื่อไอดี')" />
        <x-text-input :value="old('name', $user?->name)" id="name" name="name" type="text" class="mt-1 block w-full" required
            autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" :value="__('อีเมล')" />
        <x-text-input :value="old('email', $user?->email)" id="email" name="email" type="text" class="mt-1 block w-full" required
            autocomplete="email" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    @if ($user?->id)
        <div>
            <x-input-label for="password" :value="__('รหัสผ่าน (ถ้าไม่ต้องการแก้ไขให้ปล่อยว่างไว้)')" />
            <x-text-input id="password" name="password" type="text" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
    @else
        <div>
            <x-input-label for="password" :value="__('รหัสผ่าน')" />
            <x-text-input id="password" name="password" type="text" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
    @endif

    <div x-data="alpineUploadAvatar('{{ old('profile_image', $user?->profile_image) }}')">
        <x-input-label for="profile_image" :value="__('รูปโปรไฟล์ (ไม่บังคับ)')" />
        <x-text-input x-model="uploadFile" :value="old('profile_image', $user?->profile_image)" id="profile_image" placeholder="URL รูป"
            name="profile_image" type="text" class="mt-1 block w-full" />
        <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
            for="profile_image_upload_file">
            <input type="file" class="sr-only" id="profile_image_upload_file"
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

    <div x-data="alpineUploadImage('{{ old('cover_image', $user?->cover_image) }}')">
        <x-input-label for="cover_image" :value="__('รูปพื้นหลัง (ไม่บังคับ)')" />
        <x-text-input x-model="uploadFile" :value="old('cover_image', $user?->cover_image)" id="cover_image" placeholder="URL รูป" name="cover_image"
            type="text" class="mt-1 block w-full" />
        <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
            for="cover_image_upload_file">
            <input type="file" class="sr-only" id="cover_image_upload_file"
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
        <x-input-label for="shop_name" :value="__('ชื่อร้าน')" />
        <x-text-input id="shop_name" name="shop_name" type="text" :value="old('shop_name', $user->merchant?->name)" class="mt-1 block w-full"
            required />
        <x-input-error :messages="$errors->get('shop_name')" class="mt-2" />
    </div>

    <div x-data="alpineUploadImage('{{ old('image_url', $user->merchant?->image_url) }}')">
        <x-input-label for="image_url" :value="__('รูปร้าน')" />
        <x-text-input x-model="uploadFile" :value="old('image_url', $user->merchant?->image_url)" id="image_url" placeholder="URL รูป" name="image_url"
            type="text" class="mt-1 block w-full" required />
        <label class="border-2 border-gray-200 p-3 w-full block rounded cursor-pointer my-2"
            for="merchant_image_upload_file">
            <input type="file" class="sr-only" id="merchant_image_upload_file"
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
        <x-input-label for="desc" :value="__('รายละเอียดร้าน')" />
        <x-textarea id="desc" name="desc" class="mt-1 block w-full"
            required>{!! old('desc', $user->merchant?->desc) !!}</x-textarea>
        <x-input-error :messages="$errors->get('desc')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="address" :value="__('ที่อยู่ร้าน')" />
        <x-textarea id="address" name="address" class="mt-1 block w-full"
            required>{!! old('address', $user->merchant?->address) !!}</x-textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="province" :value="__('จังหวัด')" />
        <x-text-input id="province" name="province" :value="old('province', $user->merchant?->province)" type="text" class="mt-1 block w-full"
            required />
        <x-input-error :messages="$errors->get('province')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="referral_program" :value="__('Referral program')" />
        <x-text-input id="referral_program" name="referral_program" :value="old('referral_program', $user->merchant?->referral_program)" type="text"
            class="mt-1 block w-full @error('referral_program')
is-invalid
@enderror" />
        <x-input-error :messages="$errors->get('referral_program')" class="mt-2" />
    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>
</div>
