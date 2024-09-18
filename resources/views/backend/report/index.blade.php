<x-backend-layout>
    <x-slot name="header">
        @include('backend.report.header')
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Welcome to Report') }}
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">



                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.issue-point-status-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Issue Point Status Logs') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.mission-status-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-goal h-12 w-12">
                            <path d="M12 13V2l8 4-8 4" />
                            <path d="M20.561 10.222a9 9 0 1 1-12.55-5.29" />
                            <path d="M8.002 9.997a5 5 0 1 0 8.9 2.02" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Mission Status Logs') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.point-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-circle-dollar-sign h-12 w-12">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                            <path d="M12 18V6" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('S-Point Logs') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hidden"
                    href="{{ route('backend.reports.issue-points') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Issue Point') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.coupon-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-ticket-percent h-12 w-12">
                            <path
                                d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                            <path d="M9 9h.01" />
                            <path d="m15 9-6 6" />
                            <path d="M15 15h.01" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Coupon Logs') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.coupon-logs-by-merchant') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-ticket-percent h-12 w-12">
                            <path
                                d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                            <path d="M9 9h.01" />
                            <path d="m15 9-6 6" />
                            <path d="M15 15h.01" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Coupon Logs By Merchant') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.user-activity-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-arrow-right-left h-12 w-12">
                            <path d="m16 3 4 4-4 4" />
                            <path d="M20 7H4" />
                            <path d="m8 21-4-4 4-4" />
                            <path d="M4 17h16" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('User Activity Logs') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.cron-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-arrow-right-left h-12 w-12">
                            <path d="m16 3 4 4-4 4" />
                            <path d="M20 7H4" />
                            <path d="m8 21-4-4 4-4" />
                            <path d="M4 17h16" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Cron Logs') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.point-transaction-logs') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-arrow-right-left h-12 w-12">
                            <path d="m16 3 4 4-4 4" />
                            <path d="M20 7H4" />
                            <path d="m8 21-4-4 4-4" />
                            <path d="M4 17h16" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Point Transaction Logs') }}
                        </h5>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-backend-layout>
