<x-backend-layout>
    <x-slot name="header">
        @include('backend.user-backend.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <div class="flex justify-end">
                    <a href="{{ route('backend.user-backends.create') }}"
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
                                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700"
                                        width="180px">แอคชั่น</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                @forelse ($users as $user)
                                    <tr>
                                        <td
                                            class="px-6 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                            {{ ++$i }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">
                                            <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        ชื่อ</dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->name }}</dd>
                                                </div>
                                                <div class="p-2 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                                                    <dt
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                                        อีเมล</dt>
                                                    <dd
                                                        class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-300 sm:col-span-2 sm:mt-0">
                                                        {{ $user->email }}</dd>
                                                </div>
                                            </dl>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-600">

                                            <a class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200"
                                                href="{{ route('backend.user-backends.show', $user->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <a class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200"
                                                href="{{ route('backend.user-backends.edit', $user->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
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
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
