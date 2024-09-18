<x-backend-layout>
    <x-slot name="header">
        @include('backend.user-merchant.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.user-merchants.index') }}">
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
                            @if ($user->merchant)
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">ชื่อร้าน
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->merchant->name }}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        รูปร้าน</dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">

                                        <div class="flex items-center justify-between text-sm leading-6">
                                            <div class="flex w-0 flex-1 items-center">
                                                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                    <span
                                                        class="truncate font-medium">{{ $user->merchant->image_url }}</span>
                                                    <span class="flex-shrink-0 text-gray-400 hidden">500kb</span>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <a target="_blank" href="{{ $user->merchant->image_url }}"
                                                    class="font-medium text-green-600 hover:text-green-500">Download</a>
                                            </div>
                                        </div>
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        รายละเอียดร้าน
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->merchant->desc }}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        ที่อยู่ร้าน
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->merchant->address }}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        จังหวัด
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->merchant->province }}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Referral program</dt>

                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        @if ($user->merchant->referral_program)
                                            <a
                                                href="{{ route('backend.users.show', $user->merchant->referral_program) }}">
                                                {{ $user->merchant->referral_program }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0 flex items-center justify-between">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            Product 10 รายการล่าสุดของร้านนี้
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-merchants.banner-products', ['id' => $user->merchant->user_id]) }}"
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
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $bannerProducts =
                                                            $user->merchant->getLatestBannerProducts() ?? [];
                                                    @endphp
                                                    @forelse ($bannerProducts as $bannerProduct)
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
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerProduct->getRedeemPoints() }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            จำนวนคงเหลือที่แลกได้</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerProduct->getAvailableRedeems() }}
                                                                        </dd>
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
                                            Promotion 10 รายการล่าสุดของร้านนี้
                                        </h3>
                                        <div class="flex justify-end">
                                            <a href="{{ route('backend.user-merchants.banner-promotions', ['id' => $user->merchant->user_id]) }}"
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
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800">
                                                    @php
                                                        $bannerPromotions =
                                                            $user->merchant->getLatestBannerPromotions() ?? [];
                                                    @endphp
                                                    @forelse ($bannerPromotions as $bannerPromotion)
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
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerPromotion->getAcquirePoints() }}
                                                                        </dd>
                                                                    </div>
                                                                    <div
                                                                        class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                                        <dt
                                                                            class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                                            จำนวนคงเหลือที่ทำภารกิจได้</dt>
                                                                        <dd
                                                                            class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                                            {{ $bannerPromotion->getAvailableMissions() }}
                                                                        </dd>
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
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-backend-layout>
