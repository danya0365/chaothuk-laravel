<x-backend-layout>
    <x-slot name="header">
        @include('backend.user-customer.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.user-customers.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-3">
                <img src="{{ $user->getCoverImage() }}" class="w-full aspect-[16/9] object-cover" />
                <div class="flex justify-center -mt-8">
                    <img src="{{ $user->getAvatar(128) }}"
                        class="rounded-full border-solid border-white dark:border-gray-800 border-2 -mt-10 h-[128px] aspect-[1/1] object-cover">
                </div>
                <div class="text-center px-3 pb-6 pt-2">
                    <h3 class="text-sm bold font-sans">{{ $user->getFullName() }}</h3>
                    <p class="mt-2 font-sans font-light ">{{ $user->location }}</p>
                </div>
                <div class="p-4">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">รายละเอียดข้อมูล
                        </h3>
                    </div>
                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">
                        <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Id</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $user->id }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    บทบาท</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $user->role->name }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">ชื่อไอดี</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    <div class="flex items-center">
                                        <div class="mr-2">
                                            <img class="w-6 h-6 rounded-full" src="{{ $user->getAvatar() }}" />
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    แต้มที่ได้รับ</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                    x-numberformat>
                                    {{ $user->receivedPoints() }}</dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    แต้มที่ใช้ได้</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                    x-numberformat>
                                    {{ $user->availablePoints() }}</dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    ใช้แต้มไปแล้ว</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                    x-numberformat>
                                    {{ $user->redeemPoints() }}</dd>
                            </div>

                            @if ($user->customer)


                                @if ($user->customer->isNatural())
                                    <div class="p-4 border border-gray-100 dark:border-gray-700">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                                ข้อมูลบุคคลธรรมดา
                                            </h3>
                                        </div>
                                        <div class="mt-6 border-t border-gray-100 dark:border-gray-700">
                                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('ชื่อนามสกุล') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->getFullName() }}</dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('เพศ') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->getGenderFormat() }}</dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('เลขบัตรประจำตัวประชาชน') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->identificationNo }}</dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('วันเดือนปีเกิด ') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->birthDate }}
                                                        <span>(อายุ {{ $user->customer->getPersonInfo()->getAge() }}
                                                            ปี)</span>
                                                    </dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('เบอร์มือถือ') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->mobilePhone }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->customer->isJuristic())
                                    <div class="p-4 border border-gray-100 dark:border-gray-700">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                                ข้อมูลนิติบุคคล
                                            </h3>
                                        </div>
                                        <div class="mt-6 border-t border-gray-100 dark:border-gray-700">
                                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('ชื่อนิติบุคคล') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->juristicName }}</dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('เลขทะเบียนนิติบุคคล') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->juristicId }}</dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('วันที่จดทะเบียน') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->registrationDate }}</dd>
                                                </div>
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('เบอร์ติดต่อ') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->customer->getPersonInfo()->contactNumber }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                @endif
                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            ประเภทลูกค้า
                                        </h3>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">

                                        <div
                                            class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                                            <table class="w-full table-auto text-sm text-lef">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ลำดับ</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ข้อมูล</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $userTypeMaps = $user->userTypeMaps ?? [];
                                                    @endphp
                                                    @forelse ($userTypeMaps as $userTypeMap)
                                                        <tr>
                                                            <td
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                {{ $loop->index + 1 }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <dl
                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            ชื่อ</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $userTypeMap->userType->name }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            จำนวน</dt>
                                                                        <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                                                            x-numberformat>
                                                                            {{ $userTypeMap->amount }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            เงื่อนไข</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $userTypeMap->text_condition }}</dd>
                                                                    </div>
                                                                </dl>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4"
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <div
                                                                    class="p-6 text-gray-900 dark:text-white text-center">
                                                                    {{ __('ยังไม่มีข้อมูล') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0 flex items-center justify-between">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            10 คูปองล่าสุดของลูกค้าใน Product
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-customers.coupons', ['id' => $user->customer->user_id]) }}"
                                                class="px-2 py-1 rounded-md bg-blue-500 text-sky-100 hover:bg-blue-700">ดูทั้งหมด</a>
                                        </div>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">
                                        <div
                                            class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                                            <table class="w-full table-auto text-sm text-lef">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ลำดับ</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ข้อมูล</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            วันที่สร้าง</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $coupons = $user->customer->getLatestCoupons() ?? [];
                                                    @endphp
                                                    @forelse ($coupons as $coupon)
                                                        @php
                                                            $bannerProduct = $coupon->bannerProduct;
                                                        @endphp
                                                        <tr>
                                                            <td
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                {{ $loop->index + 1 }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <dl
                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            ร้านค้า</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $coupon?->bannerProduct->merchant->getMerchantName() ?? 'ยังไม่ได้ระบุ' }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            ชื่อ</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerProduct->name }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            แท็ก</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerProduct->textTags() }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            แต้มที่ใช้แลก</dt>
                                                                        <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                                                            x-numberformat>
                                                                            {{ $bannerProduct->redeem_points }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            คูปองโค้ด</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $coupon->code }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            สถานะคูปอง</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $coupon->getCouponAvailableStatusFormat() }}
                                                                        </dd>
                                                                    </div>
                                                                </dl>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600"
                                                                x-datetimeformat>
                                                                {{ $coupon->created_at }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4"
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <div
                                                                    class="p-6 text-gray-900 dark:text-white text-center">
                                                                    {{ __('ยังไม่มีข้อมูล') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0 flex items-center justify-between">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            10 ภารกิจล่าสุดของลูกค้า ใน Promotion
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-customers.missions', ['id' => $user->customer->user_id]) }}"
                                                class="px-2 py-1 rounded-md bg-blue-500 text-sky-100 hover:bg-blue-700">ดูทั้งหมด</a>
                                        </div>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">

                                        <div
                                            class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                                            <table class="w-full table-auto text-sm text-lef">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ลำดับ</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ข้อมูล</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            วันที่สร้าง</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $missions = $user->customer->getLatestMissions() ?? [];
                                                    @endphp
                                                    @forelse ($missions as $mission)
                                                        @php
                                                            $bannerPromotion = $mission->bannerPromotion;
                                                        @endphp
                                                        <tr>
                                                            <td
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                {{ $loop->index + 1 }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                                                                <dl
                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            ชื่อ</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerPromotion->name }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            แท็ก</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerPromotion->textTags() }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            แต้มที่ได้</dt>
                                                                        <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                                                            x-numberformat>
                                                                            {{ $bannerPromotion->acquire_points }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            สถานะภารกิจ</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $mission->getMissionStatusFormat() }}
                                                                        </dd>
                                                                    </div>
                                                                </dl>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600"
                                                                x-datetimeformat>
                                                                {{ $mission->created_at }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4"
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <div
                                                                    class="p-6 text-gray-900 dark:text-white text-center">
                                                                    {{ __('ยังไม่มีข้อมูล') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0 flex items-center justify-between">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            10 issue point ล่าสุดของลูกค้า
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-customers.issue-points', ['id' => $user->customer->user_id]) }}"
                                                class="px-2 py-1 rounded-md bg-blue-500 text-sky-100 hover:bg-blue-700">ดูทั้งหมด</a>
                                        </div>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">

                                        <div
                                            class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                                            <table class="w-full table-auto text-sm text-lef">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ลำดับ</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ข้อมูล</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            วันที่สร้าง</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $issuePoints = $user->customer->getLatestIssuePoints() ?? [];
                                                    @endphp
                                                    @forelse ($issuePoints as $issuePoint)
                                                        <tr>
                                                            <td
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                {{ $loop->index + 1 }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                                                                <dl
                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            slug</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $issuePoint->slug }}</dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            แต้มที่ได้</dt>
                                                                        <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                                                            x-numberformat>
                                                                            {{ $issuePoint->points }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            สถานะ</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $issuePoint->status }}
                                                                        </dd>
                                                                    </div>
                                                                </dl>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600"
                                                                x-datetimeformat>
                                                                {{ $issuePoint->created_at }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3"
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <div
                                                                    class="p-6 text-gray-900 dark:text-white text-center">
                                                                    {{ __('ยังไม่มีข้อมูล') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0 flex items-center justify-between">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            10 ประวัติความเคลื่อนไหว Point ล่าสุดของลูกค้า
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-customers.point-logs', ['id' => $user->customer->user_id]) }}"
                                                class="px-2 py-1 rounded-md bg-blue-500 text-sky-100 hover:bg-blue-700">ดูทั้งหมด</a>
                                        </div>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">

                                        <div
                                            class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                                            <table class="w-full table-auto text-sm text-lef">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ลำดับ</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ข้อมูล</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            วันที่สร้าง</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $pointLogs = $user->customer->getLatestPointLogs() ?? [];
                                                    @endphp
                                                    @forelse ($pointLogs as $pointLog)
                                                        <tr>
                                                            <td
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                {{ $loop->index + 1 }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                                                                <dl
                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                    @if ($pointLog->points > 0)
                                                                        <div
                                                                            class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                            <dt
                                                                                class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                                แต้มที่ได้รับ</dt>
                                                                            <dd
                                                                                class="mt-1 text-sm leading-6 text-green-700 dark:text-green-300 sm:col-span-2 sm:mt-0 space-x-2">
                                                                                <span
                                                                                    x-numberformat>{{ $pointLog->points }}</span>
                                                                                @php
                                                                                    $issuePoint =
                                                                                        $pointLog->userPoint
                                                                                            ?->issuePoint;
                                                                                @endphp
                                                                                @if ($issuePoint)
                                                                                    จาก <a
                                                                                        href="{{ route('backend.issue-points.show', $issuePoint->id) }}">{{ $issuePoint->name }}</a>
                                                                                @endif
                                                                            </dd>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                            <dt
                                                                                class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                                ใช้แต้ม</dt>
                                                                            <dd
                                                                                class="mt-1 text-sm leading-6 text-red-700 dark:text-red-300 sm:col-span-2 sm:mt-0 space-x-2">
                                                                                <span
                                                                                    x-numberformat>{{ $pointLog->points }}</span>
                                                                                @php
                                                                                    $userCoupon = $pointLog->userCoupon;
                                                                                @endphp
                                                                                @if ($userCoupon)
                                                                                    แลก <a
                                                                                        href="{{ route('backend.banner-products.show', $userCoupon->bannerProduct->id) }}">{{ $userCoupon->bannerProduct->name }}</a>
                                                                                @endif
                                                                            </dd>
                                                                        </div>
                                                                    @endif
                                                                </dl>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600"
                                                                x-datetimeformat>
                                                                {{ $pointLog->created_at }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3"
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <div
                                                                    class="p-6 text-gray-900 dark:text-white text-center">
                                                                    {{ __('ยังไม่มีข้อมูล') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0 flex items-center justify-between">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            10 ประวัติความเคลื่อนไหว Coupon ล่าสุดของลูกค้า
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-customers.coupon-logs', ['id' => $user->customer->user_id]) }}"
                                                class="px-2 py-1 rounded-md bg-blue-500 text-sky-100 hover:bg-blue-700">ดูทั้งหมด</a>
                                        </div>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">

                                        <div
                                            class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                                            <table class="w-full table-auto text-sm text-lef">
                                                <thead
                                                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ลำดับ</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            ข้อมูล</th>
                                                        <th
                                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                                                            วันที่สร้าง</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $couponLogs = $user->customer->getLatestCouponLogs() ?? [];
                                                    @endphp
                                                    @forelse ($couponLogs as $couponLog)
                                                        <tr>
                                                            <td
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                {{ $loop->index + 1 }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                                                                <dl
                                                                    class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                    @if ($couponLog->coupons > 0)
                                                                        <div
                                                                            class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                            <dt
                                                                                class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                                ได้รับ</dt>
                                                                            <dd
                                                                                class="mt-1 text-sm leading-6 text-green-700 dark:text-green-300 sm:col-span-2 sm:mt-0">
                                                                                <a
                                                                                    href="{{ route('backend.user-coupons.show', $couponLog->user_coupon_id) }}">
                                                                                    <span
                                                                                        x-numberformat>{{ $couponLog->coupons }}</span>
                                                                                    คูปอง
                                                                                </a>
                                                                            </dd>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                            <dt
                                                                                class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                                ใช้ไป</dt>
                                                                            <dd
                                                                                class="mt-1 text-sm leading-6 text-red-700 dark:text-red-300 sm:col-span-2 sm:mt-0">
                                                                                <a
                                                                                    href="{{ route('backend.user-coupons.show', $couponLog->user_coupon_id) }}">
                                                                                    <span
                                                                                        x-numberformat>{{ $couponLog->coupons }}</span>
                                                                                    คูปอง
                                                                                </a>
                                                                            </dd>
                                                                        </div>
                                                                    @endif
                                                                </dl>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600"
                                                                x-datetimeformat>
                                                                {{ $couponLog->created_at }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3"
                                                                class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                                                <div
                                                                    class="p-6 text-gray-900 dark:text-white text-center">
                                                                    {{ __('ยังไม่มีข้อมูล') }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Referral program (User Customer Id)</dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->customer->referral_program }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-backend-layout>
