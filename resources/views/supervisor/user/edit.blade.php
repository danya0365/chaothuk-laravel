<x-supervisor-layout>
    <x-slot name="header">
        @include('supervisor.user.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-3xl font-bold">
                    Update Work
                </h1>
                <div class="flex justify-end mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600" href="{{ route('supervisor.users.index') }}">
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

                            <form action="{{ route('supervisor.users.update', $user->id) }}" method="POST">
                                {{ method_field('PATCH') }}
                                @csrf
                                
                                <div class="flex flex-col space-y-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="name">Name</label>
                                        <input value="{{ $user->name }}" type="text" name="name" placeholder="Name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="email">Email</label>
                                        <input value="{{ $user->email }}" type="email" name="email" placeholder="Email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="password">Password (ถ้าไม่ต้องการแก้ไขให้ปล่อยว่างไว้)</label>
                                        <input value="" type="text" name="password" placeholder="Password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="profile_image">Profile Image</label>
                                        <input value="{{ $user->profile_image }}" type="text" name="profile_image" placeholder="URL รูป" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="cover_image">Cover Image</label>
                                        <input value="{{ $user->cover_image }}" type="text" name="cover_image" placeholder="URL รูป" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="first_name">First name</label>
                                        <input value="{{ $user->first_name }}" type="text" name="first_name" placeholder="First name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="last_name">Last name</label>
                                        <input value="{{ $user->last_name }}" type="text" name="last_name" placeholder="Last name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="birth_date">Birth date</label>
                                        <input value="{{ $user->birth_date }}" type="date" name="birth_date" placeholder="YYYY-MM-DD" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="mobile_phone">Mobile phone</label>
                                        <input value="{{ $user->mobile_phone }}" type="text" name="mobile_phone" placeholder="08x-xxx-xxxx" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="location">Location</label>
                                        <input value="{{ $user->location }}" type="text" name="location" placeholder="Location" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700" for="biography">Biography</label>
                                        <textarea name="biography" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm placeholder:text-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $user->biography }}</textarea>
                                    </div>

                                    <div class="flex items-center justify-start mt-4 gap-x-2">
                                        <button type="submit" class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-supervisor-layout>