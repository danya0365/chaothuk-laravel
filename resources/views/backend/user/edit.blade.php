<x-backend-layout>
    <x-slot name="header">
        @include('backend.user.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <h1 class="text-3xl font-bold">
                    Update
                </h1>
                <p class="my-1 max-w-2xl text-sm leading-6 text-red-500 font-bold">**ต้องกรอกข้อมูลให้ครบถ้วน**</p>
                @if (!Auth::user()->role?->isSupervisor())
                    <div class="p-4 rounded bg-red-500 text-red-100 mb-4">
                        <span>เฉพาะ{{ __('common.role-1') }}เท่านั้น ที่สามารถแก้ไขข้อมูล</span>
                    </div>
                @endif
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.users.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="flex flex-col mt-5">
                <div class="flex flex-col">
                    <div
                        class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">

                        @if ($errors->any())
                            <div class="p-4 rounded bg-red-500 text-red-100 mb-4">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div
                            class="w-full px-6 py-4 bg-white dark:bg-gray-800 rounded shadow-md ring-1 ring-gray-900/10">

                            <form action="{{ route('backend.users.update', $user->id) }}" method="POST">
                                {{ method_field('PATCH') }}
                                @csrf

                                @include('backend.user.form')
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-backend-layout>
