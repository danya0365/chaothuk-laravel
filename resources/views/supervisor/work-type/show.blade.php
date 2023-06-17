<x-supervisor-layout>
    <x-slot name="header">
        @include('supervisor.work-type.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">

            <div class="mb-4">
                <div class="flex justify-end mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600" href="{{ route('supervisor.work-types.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="px-4 sm:px-0">
                  <h3 class="text-base font-semibold leading-7 text-gray-900">Information</h3>
                  <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Details and all other fields.</p>
                </div>
                <div class="mt-6 border-t border-gray-100">
                  <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Id</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $workType->id }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                      <dt class="text-sm font-medium leading-6 text-gray-900">Title</dt>
                      <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $workType->title }}</dd>
                    </div>
                  </dl>
                </div>
              </div>


        </div>

    </div>
</x-supervisor-layout>
