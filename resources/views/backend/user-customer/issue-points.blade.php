<x-backend-layout>
    <x-slot name="header">
        @include('backend.user-customer.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.user-customers.show', $user->id) }}">
                        < Back</a>
                </div>
            </div>

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex justify-end ">
                    <a href="{{ route('backend.user-missions.create', ['customerId' => $user->id]) }}"
                        class="px-2 py-1 rounded-md bg-blue-500 text-sky-100 hover:bg-blue-700">+ Create New</a>
                </div>
            </div>

            <div class="flex flex-col mt-4 space-y-2">
                <div class="flex flex-col">
                    <div
                        class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                        @if ($message = Session::get('success'))
                            <div class="p-4 rounded bg-green-500 text-green-100 mb-4 m-3">
                                <span>{{ $message }}</span>
                            </div>
                        @endif
                        @if ($message = Session::get('error'))
                            <div class="p-4 rounded bg-red-500 text-red-100 mb-4 m-3">
                                <span>{{ $message }}</span>
                            </div>
                        @endif

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
                                @forelse ($issuePoints as $issuePoint)
                                    <tr>
                                        <td
                                            class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                            {{ $loop->index + 1 }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        slug</dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $issuePoint->slug }}</dd>
                                                </div>
                                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        แต้มที่ได้</dt>
                                                    <dd class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0"
                                                        x-numberformat>
                                                        {{ $issuePoint->points }}
                                                    </dd>
                                                </div>
                                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
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

                <div class="flex flex-col">
                    {!! $issuePoints->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
