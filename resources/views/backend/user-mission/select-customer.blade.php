<div class="px-4 sm:px-0 flex items-center justify-between">
    <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
        เลือก Customer
    </h3>
</div>
<div class="mt-6 border-t border-gray-100 dark:border-gray-700">

    <div
        class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

        <table class="w-full table-auto text-sm text-lef">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium border-b">
                <tr>
                    <th
                        class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        ลำดับ</th>
                    <th
                        class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                        ข้อมูล</th>
                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"
                        width="180px">เลือก</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                            {{ ++$i }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        ชื่อ</dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->name }}</dd>
                                </div>
                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        อีเมล</dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->email }}</dd>
                                </div>
                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        ชื่อลูกค้า</dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        {{ $user->customer?->getPersonInfo()->getFullName() ?? 'ยังไม่ได้ระบุ' }}
                                    </dd>
                                </div>
                            </dl>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                            <a class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200"
                                href="{{ route('backend.user-missions.create', ['customerId' => $user->id, 'bannerPromotionId' => $bannerPromotionId]) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-6 h-6 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>

                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3"
                            class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                            <div class="p-6 text-gray-900 dark:text-white text-center">
                                {{ __('ยังไม่มีข้อมูล') }}
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
