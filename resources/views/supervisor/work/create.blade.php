<x-supervisor-layout>
    <x-slot name="header">
        @include('supervisor.work.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-3xl font-bold">
                    Create New
                </h1>
                <div class="flex justify-end mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600" href="{{ route('supervisor.works.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="flex flex-col mt-5">
                <div class="flex flex-col">
                    <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">

                        @if ($errors->any())
                        <div class="p-4 rounded bg-red-500 text-white mb-4">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="w-full px-6 py-4 bg-white rounded shadow-md ring-1 ring-gray-900/10">

                            <form action="{{ route('supervisor.works.store') }}" method="POST">
                                @csrf

                                <div>
                                    <label class="block text-sm font-bold text-gray-700" for="title">Code</label>
                                    <input type="text" name="code" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Code">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700" for="title">Title</label>
                                    <input type="text" name="title" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Title">
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700" for="title">Description:</label>
                                    <textarea class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="description" placeholder="Description"></textarea>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700" for="title">Details:</label>
                                    <textarea class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="details" placeholder="Details"></textarea>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700" for="title">Province Id</label>
                                    <input type="text" name="province_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Province Id">
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700" for="title">Work Type Id</label>
                                    <input type="text" name="work_type_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Province Id">
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700" for="title">Author Id</label>
                                    <input type="text" name="author_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Author Id">
                                </div>

                                <div class="flex items-center justify-start mt-4 gap-x-2">
                                    <button type="submit" class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-supervisor-layout>