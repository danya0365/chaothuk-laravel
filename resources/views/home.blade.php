<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if (session('error'))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-red-600">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="alpineData()">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Welcome to Landing Page') }}
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mt-4">
                <a class="flex h-[150px] items-center justify-center text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    href="{{ route('messenger.mobile-phone-channel.new') }}">
                    <div class="flex-1 w-full flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>


                        <h5 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">
                            {{ __('ติดต่อเจ้าหน้าที่') }}
                        </h5>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <x-slot name="javascript">
        <script type="text/javascript">
            function alpineData() {
                console.log('routes', route('home'));
            }
        </script>
    </x-slot>
</x-app-layout>
