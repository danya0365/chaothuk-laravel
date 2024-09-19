<div class="flex flex-col space-y-4">

    <div>
        <x-input-label for="name" :value="__('ชื่อบทบาท')" />
        <x-text-input :value="old('name', $role?->name)" id="name" name="name" type="text" class="mt-1 block w-full" required
            autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
                                $permissionMaps = $role?->rolePermissions ?? [];
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
                                    $data = "role_permissions[{$permission['id']}][data]";
                                    $desc = "role_permissions[{$permission['id']}][desc]";

                                    //dd($currentPermissionMap?->data);

                                    $dataValue = $currentPermissionMap?->data ? 1 : 0;
                                @endphp
                                <tr x-data="{
                                    autoChecked() {
                                        checkboxes = document.querySelectorAll('[id=role_permissions-{{ $permission['id'] }}]');
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
                                            <label for="role_permissions-{{ $permission['id'] }}"
                                                class="block ms-2  text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">
                                                {{ $permission['label'] }}
                                            </label>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <x-text-input name="{{ $data }}" :value="old($data, $dataValue)" type="text"
                                                class="mt-1 block w-full" x-on:keyup="autoChecked()" />
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <x-text-input name="{{ $desc }}" :value="old($desc, $currentPermissionMap?->desc)" type="text"
                                                class="mt-1 block w-full" x-on:keyup="autoChecked()" />
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <input {{ $currentPermissionMap ? 'checked' : '' }}
                                            id="role_permissions-{{ $permission['id'] }}" type="checkbox"
                                            name="role_permissions[{{ $permission['id'] }}][permission_id]"
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
