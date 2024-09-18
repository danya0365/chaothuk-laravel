<div class="flex flex-col space-y-4" x-data="alpineFormData('{{ old('person_type', $user?->customer?->person_type) }}')">
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
        <x-input-label for="person_type" :value="__('ประเภทบุคคล')" />
        <x-radio-input name="person_type" class="mt-1 block w-full" required :selections="$personTypeSelections" :selected="$user?->customer?->person_type"
            x-model="personType" />
        <x-input-error :messages="$errors->get('person_type')" class="mt-2" />
    </div>

    <template x-if="isNatural()">
        <div class="flex flex-col space-y-4" x-init="$nextTick(() => {
            bindDatepicker()
        })">
            <h2>ข้อมูลสำหรับบุคคลธรรมดา</h2>

            <div>
                <x-input-label for="first_name" :value="__('ชื่อจริง')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" required
                    :value="$user?->customer?->getPersonInfo()->firstName" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('นามสกุล')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" required
                    :value="$user?->customer?->getPersonInfo()->lastName" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('เพศ')" />
                <x-radio-input name="gender" class="mt-1 block w-full" required :selections="$genderSelections"
                    :selected="$user?->customer?->getPersonInfo()->gender" />
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="identification_no" :value="__('เลขบัตรประจำตัวประชาชน')" />
                <x-text-input id="identification_no" name="identification_no" type="text"
                    class="mt-1 block w-full" required :value="$user?->customer?->getPersonInfo()->identificationNo" />
                <x-input-error :messages="$errors->get('identification_no')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="birth_date" :value="__('วันเดือนปีเกิด')" />
                <x-text-input id="birth_date" name="birth_date" type="text" class="mt-1 block w-full date"
                    required :value="$user?->customer?->getPersonInfo()->birthDate" />
                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="mobile_phone" :value="__('เบอร์มือถือ')" />
                <x-text-input id="mobile_phone" name="mobile_phone" type="text" class="mt-1 block w-full"
                    required :value="$user?->customer?->getPersonInfo()->mobilePhone" />
                <x-input-error :messages="$errors->get('mobile_phone')" class="mt-2" />
            </div>

        </div>
    </template>

    <template x-if="isJuristic()">
        <div class="flex flex-col space-y-4" x-init="$nextTick(() => {
            bindDatepicker()
        })">
            <h2>ข้อมูลสำหรับนิติบุคคล</h2>

            <div>
                <x-input-label for="juristic_name" :value="__('ชื่อนิติบุคคล')" />
                <x-text-input id="juristic_name" name="juristic_name" type="text" class="mt-1 block w-full"
                    required :value="$user?->customer?->getPersonInfo()->juristicName" />
                <x-input-error :messages="$errors->get('juristic_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="juristic_id" :value="__('เลขทะเบียนนิติบุคคล')" />
                <x-text-input id="juristic_id" name="juristic_id" type="text" class="mt-1 block w-full" required
                    :value="$user?->customer?->getPersonInfo()->juristicId" />
                <x-input-error :messages="$errors->get('juristic_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="registration_date" :value="__('วันที่จดทะเบียน')" />
                <x-text-input id="registration_date" name="registration_date" type="text"
                    class="mt-1 block w-full date" required :value="$user?->customer?->getPersonInfo()->registrationDate" />
                <x-input-error :messages="$errors->get('registration_date')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="contact_number" :value="__('เบอร์ติดต่อ')" />
                <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full"
                    required :value="$user?->customer?->getPersonInfo()->contactNumber" />
                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
            </div>

        </div>
    </template>

    <div>
        <x-input-label for="referral_program" :value="__('Referral program')" />
        <x-text-input id="referral_program" name="referral_program" :value="old('referral_program', $user?->customer?->referral_program)" type="text"
            class="mt-1 block w-full @error('referral_program')
is-invalid
@enderror" />
        <x-input-error :messages="$errors->get('referral_program')" class="mt-2" />
    </div>

    <div>
        <x-input-label :value="__('ประเภทลูกค้า')" />
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
                                    {{ __('No') }}</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('Name') }}</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('Amount') }}</th>
                                <th
                                    class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                    {{ __('Condition') }}</th>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"
                                    width="180px">{{ __('Select') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @php
                                $userTypeMaps = $user?->userTypeMaps ?? [];
                            @endphp
                            @foreach ($userTypeSelections as $userType)
                                @php
                                    $currentUserTypeMap = null;
                                    if ($userTypeMaps) {
                                        foreach ($userTypeMaps as $userTypeMap) {
                                            if ($userTypeMap->user_type_id == $userType['value']) {
                                                $currentUserTypeMap = $userTypeMap;
                                            }
                                        }
                                    }
                                    $amount = "user_types[{$userType['id']}][amount]";
                                    $textCondition = "user_types[{$userType['id']}][text_condition]";
                                @endphp
                                <tr x-data="{
                                    autoChecked() {
                                        checkboxes = document.querySelectorAll('[id=user_type-{{ $userType['id'] }}]');
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
                                            <label for="user_type-{{ $userType['id'] }}"
                                                class="block ms-2  text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">
                                                {{ $userType['label'] }}
                                            </label>
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <x-text-input name="{{ $amount }}" :value="old($amount, $currentUserTypeMap?->amount)"
                                                type="text" class="mt-1 block w-full"
                                                x-on:keyup="autoChecked()" />
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center">
                                            <x-text-input name="{{ $textCondition }}" :value="old($textCondition, $currentUserTypeMap?->text_condition)"
                                                type="text" class="mt-1 block w-full"
                                                x-on:keyup="autoChecked()" />
                                        </div>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                        <input {{ $currentUserTypeMap ? 'checked' : '' }}
                                            id="user_type-{{ $userType['id'] }}" type="checkbox"
                                            name="user_types[{{ $userType['id'] }}][user_type_id]"
                                            value="{{ $userType['value'] }}"
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
<x-slot name="javascript">
    <script type="text/javascript">
        function alpineFormData(personType) {
            return {
                personType: personType,
                isNatural() {
                    return this.personType === 'natural'
                },
                isJuristic() {
                    return this.personType === 'juristic'
                },
                bindDatepicker() {
                    flatpickr(".date", {
                        enableTime: false,
                        altInput: true,
                        altFormat: "l j F Y",
                    });
                }
            }
        }
    </script>
</x-slot>
