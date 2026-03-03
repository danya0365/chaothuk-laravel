<x-backend-layout>
    <x-slot name="header">
        @include('backend.issue-point.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.issue-points.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-3">
                <div class="p-4">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">รายละเอียดข้อมูล
                        </h3>
                    </div>
                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700" x-data="alpineFormData()">
                        <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Id</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $issuePoint->id }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Slug</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $issuePoint->slug }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">ชื่อ</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $issuePoint->name }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    รายละเอียดเพิ่มเติม</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $issuePoint->desc }}</dd>
                            </div>

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">ประเภท</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $issuePoint->getTypeFormat() }}</dd>
                            </div>
                            @if ($issuePoint->isTypeRepeat())
                                <div class="p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="px-4 sm:px-0">
                                        <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-100">
                                            ข้อมูลสำหรับการทำซ้ำ
                                        </h3>
                                    </div>
                                    <div class="mt-6 border-t border-gray-100 dark:border-gray-700">
                                        <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt
                                                    class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                    {{ __('เริ่มวันที่') }}
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                    {{ $issuePoint?->getStartDateFormat() }}</dd>
                                            </div>
                                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                <dt
                                                    class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                    {{ __('สิ้นสุดวันที่') }}
                                                </dt>
                                                <dd
                                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                    {{ $issuePoint?->getEndDateFormat() }}</dd>
                                            </div>
                                            <template x-if="isRepeatTypeWeekDayInWeek()">
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('ทำซ้ำ') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        ทุกวัน{{ $issuePoint?->cron()->getRepeatWeekDayFormat() }}ของสัปดาห์
                                                    </dd>
                                                </div>
                                            </template>
                                            <template x-if="isRepeatTypeDateInMonth()">
                                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        {{ __('ทำซ้ำ') }}
                                                    </dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        ทุกวันที่ {{ $issuePoint?->cron()->getRepeatValue() }} ของเดือน
                                                    </dd>
                                                </div>
                                            </template>
                                        </dl>
                                    </div>
                                </div>
                            @endif

                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    จำนวนแต้ม</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                    x-numberformat>
                                    {{ $issuePoint->points }}</dd>
                            </div>

                            @if ($issuePoint->userMission?->bannerPromotion)
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        มิชชั่น</dt>
                                    <dd
                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                        <a
                                            href="{{ route('backend.user-missions.show', $issuePoint->userMission->id) }}">{{ $issuePoint->userMission->bannerPromotion->name }}</a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <x-slot name="javascript">
        <script type="text/javascript">
            function alpineFormData() {
                const data = {!! $issuePoint->toJson() !!}
                return {
                    issueType: data.type,
                    repeatType: data.cron_info.repeat_type,
                    repeatValue: data.cron_info.repeat_value,
                    isRepeat() {
                        return this.issueType === '{{ \App\Enums\IssueType::REPEAT->value }}'
                    },
                    isNotEmptyRepeatType() {
                        return this.repeatType != ''
                    },
                    isRepeatTypeWeekDayInWeek() {
                        return this.repeatType === '{{ \App\Enums\CronRepeatType::AT_WEEKDAY_IN_WEEK->value }}'
                    },
                    isRepeatTypeDateInMonth() {
                        return this.repeatType === '{{ \App\Enums\CronRepeatType::AT_DATE_IN_MONTH->value }}'
                    },
                }
            }
        </script>
    </x-slot>

</x-backend-layout>
