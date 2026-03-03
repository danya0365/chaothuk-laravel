<x-backend-layout>
    <x-slot name="header">
        @include('backend.work.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.works.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-3" x-data="alpineData()">
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
                                    {{ $work->id }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    ทะเบียนรถ</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $work->code }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    ชื่อ</dt>
                                <dd
                                    class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                    {{ $work->title }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    ราคา</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                    x-numberformat>
                                    {{ $work->price }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    {{ __('รูปแกลเลอรี่') }}
                                </dt>
                                <dd class="mt-2 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    <ul role="list"
                                        class="divide-y divide-gray-100 dark:divide-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                                        @forelse ($work->images as $galleryImage)
                                            <li
                                                class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                <div class="flex w-0 flex-1 items-center">
                                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                        <span class="truncate font-medium">{{ $galleryImage }}</span>
                                                        <span class="flex-shrink-0 text-gray-400 hidden">500kb</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <a target="_blank" href="{{ $galleryImage }}"
                                                        class="font-medium text-green-600 hover:text-green-500">Download</a>
                                                </div>
                                            </li>
                                        @empty
                                            <li
                                                class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                                <div class="flex w-0 flex-1 items-center">
                                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                                        <span class="truncate font-medium">Empty</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforelse
                                    </ul>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <x-slot name="javascript">
        <script type="text/javascript">
            function alpineData() {
                const work = {!! $work->toJson() !!}
                console.log('work', work);
                return {
                    work
                }
            }
        </script>
    </x-slot>
</x-backend-layout>
