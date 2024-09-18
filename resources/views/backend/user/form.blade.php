<div class="flex flex-col space-y-4">

    <div>
        <x-input-label for="role_id" :value="__('บทบาท')" />
        <x-radio-input name="role_id" class="mt-1 block w-full" required :selections="$roleSelections" :selected="$user?->role_id" />
        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
    </div>

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

    @if ($user)
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
        <x-input-label for="first_name" :value="__('ชื่อจริง (ไม่บังคับ)')" />
        <x-text-input :value="old('first_name', $user?->first_name)" id="first_name" name="first_name" type="text" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="last_name" :value="__('นามสกุล (ไม่บังคับ)')" />
        <x-text-input :value="old('last_name', $user?->last_name)" id="last_name" name="last_name" type="text" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="birth_date" :value="__('วันเดือนปีเกิด (ไม่บังคับ)')" />
        <x-text-input :value="old('birth_date', $user?->birth_date)" id="birth_date" name="birth_date" type="text"
            class="mt-1 block w-full datetime" />
        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="mobile_phone" :value="__('เบอร์มือถือ (ไม่บังคับ)')" />
        <x-text-input :value="old('mobile_phone', $user?->mobile_phone)" id="mobile_phone" name="mobile_phone" type="text"
            class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('mobile_phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="location" :value="__('ที่อยู่ (ไม่บังคับ)')" />
        <x-text-input :value="old('location', $user?->location)" id="location" name="location" type="text" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('location')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="biography" :value="__('ประวัติโดยย่อ (ไม่บังคับ)')" />
        <x-textarea id="biography" name="biography" type="text"
            class="mt-1 block w-full">{!! old('location', $user?->biography) !!}</x-textarea>
        <x-input-error :messages="$errors->get('biography')" class="mt-2" />
    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>
</div>
