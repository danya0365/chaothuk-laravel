<x-backend-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Backend') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Welcome to Backend') }}
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">

                <a class="flex col-span-2 md:col-span-3 h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.configurations.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('ตั้งค่า') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.roles.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('บทบาทผู้ใช้งาน') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.users.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('ไอดีผู้ใช้งาน') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.works.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="w-12 h-12">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            <rect width="20" height="14" x="2" y="6" rx="2" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('โพสท์รับงาน') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.recruits.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="w-12 h-12">
                            <path
                                d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14" />
                            <path d="m7.5 4.27 9 5.15" />
                            <polyline points="3.29 7 12 12 20.71 7" />
                            <line x1="12" x2="12" y1="22" y2="12" />
                            <circle cx="18.5" cy="15.5" r="2.5" />
                            <path d="M20.27 17.27 22 19" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('โพสท์หางาน') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.notifications.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('การประกาศแจ้งเตือน') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.messenger-channels.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>


                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('แชต') }}
                        </h5>
                    </div>
                </a>
                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.banners.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-ribbon h-12 w-12">
                            <path
                                d="M17.75 9.01c-.52 2.08-1.83 3.64-3.18 5.49l-2.6 3.54-2.97 4-3.5-2.54 3.85-4.97c-1.86-2.61-2.8-3.77-3.16-5.44" />
                            <path
                                d="M17.75 9.01A7 7 0 0 0 6.2 9.1C6.06 8.5 6 7.82 6 7c0-3.5 2.83-5 5.98-5C15.24 2 18 3.5 18 7c0 .73-.09 1.4-.25 2.01Z" />
                            <path d="m9.35 14.53 2.64-3.31" />
                            <path d="m11.97 18.04 2.99 4 3.54-2.54-3.93-5" />
                            <path d="M14 8c0 1-1 2-2.01 3.22C11 10 10 9 10 8a2 2 0 1 1 4 0" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('แบนเนอร์') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.issue-points.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('ให้ Point') }}
                        </h5>
                    </div>
                </a>

                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hidden"
                    href="{{ route('backend.issue-points.schedule-list') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('ตั้งกำหนดการให้ Point') }}
                        </h5>
                    </div>
                </a>

                <a class="flex col-span-2 md:col-span-3 h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('backend.reports.index') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="w-12 h-12">
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                            <path d="M8 7v7" />
                            <path d="M12 7v4" />
                            <path d="M16 7v9" />
                        </svg>


                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('Report') }}
                        </h5>
                    </div>
                </a>


            </div>
        </div>
    </div>
</x-backend-layout>
