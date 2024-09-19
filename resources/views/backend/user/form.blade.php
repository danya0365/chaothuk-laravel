<div class="flex flex-col space-y-4">

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

    <div>
        <x-input-label :value="__('สิทธิที่กระทำได้')" />
        <div class="mt-1 block w-full">
            <div class="flex flex-col">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg relative overflow-x-auto">

                    <table class="w-full table-auto text-sm text-lef">
                        <thead
                            class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                            <tr>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('ลำดับ') }}</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('สิทธิ') }}</th>

                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('Yes/No') }}</th>

                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('อธิบาย') }}</th>

                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"
                                    width="180px">{{ __('Select') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @php
                                $permissionMaps = $user?->userPermissions ?? [];
                            @endphp
                            @foreach ($permissionSelections as $permission)
                                @php
                                    $currentPermissionMap = null;
                                    if ($permissionMaps) {
                                        foreach ($permissionMaps as $permissionMap) {
                                            if ($permissionMap->permission_id == $permission['value']) {
                                                $currentPermissionMap = $permissionMap;
                                            }
                                        }
                                    }
                                    $data = "user_permissions[{$permission['id']}][data]";
                                    $desc = "user_permissions[{$permission['id']}][desc]";

                                    //dd($currentPermissionMap?->data);

                                    $dataValue = $currentPermissionMap?->data ? 1 : 0;
                                @endphp
                                <tr x-data="{
                                    autoChecked() {
                                        checkboxes = document.querySelectorAll('[id=user_permissions-{{ $permission['id'] }}]');
                                        [...checkboxes].map((el) => {
                                            el.checked = true;
                                        })
                                    }
                                }">
                                    <td class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        {{ $loop->index + 1 }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <label for="user_permissions-{{ $permission['id'] }}"
                                                class="block ms-2  text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">
                                                {{ $permission['label'] }}
                                            </label>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <x-text-input name="{{ $data }}" :value="old($data, $dataValue)"
                                                type="text" class="mt-1 block w-full"
                                                x-on:keyup="autoChecked()" />
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <x-text-input name="{{ $desc }}" :value="old($desc, $currentPermissionMap?->desc)"
                                                type="text" class="mt-1 block w-full"
                                                x-on:keyup="autoChecked()" />
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <input {{ $currentPermissionMap ? 'checked' : '' }}
                                            id="user_permissions-{{ $permission['id'] }}" type="checkbox"
                                            name="user_permissions[{{ $permission['id'] }}][permission_id]"
                                            value="{{ $permission['value'] }}"
                                            class="w-4 h-4 cursor-pointer border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>
</div>
