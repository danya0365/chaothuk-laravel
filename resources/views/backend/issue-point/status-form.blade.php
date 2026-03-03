<x-backend-layout>
    <x-slot name="header">
        @include('backend.issue-point.header')
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 mx-auto sm:px-0 px-6 md:px-0 xl:px-0 lg:px-0">
                <h1 class="text-3xl font-bold">
                    อัพเดตสถานะของภารกิจ
                </h1>
                <p class="my-1 max-w-2xl text-sm leading-6 text-red-500 font-bold">**ต้องกรอกข้อมูลให้ครบถ้วน**</p>
                @if (!Auth::user()->backend?->is_can_approved)
                    <div class="p-4 rounded bg-red-500 text-red-100 mb-4">
                        <span>คุณไม่มีสิทธิอนุมัติ กรุณาขอสิทธิจากบริษัท</span>
                    </div>
                @endif
                <div class="flex mt-5">
                    <a class="px-2 py-1 rounded-md bg-sky-500 text-sky-100 hover:bg-sky-600"
                        href="{{ route('backend.issue-points.index') }}">
                        < Back</a>
                </div>
            </div>

            <div class="flex flex-col mt-5 gap-4">

                @if ($message = Session::get('success'))
                    <div class="p-4 rounded bg-green-500 text-green-100">
                        <span>{{ $message }}</span>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="p-4 rounded bg-red-500 text-red-100">
                        <span>{{ $message }}</span>
                    </div>
                @endif


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

                            <form method="POST"
                                action="{{ route('backend.issue-points.status-submit', $issuePoint->id) }}"
                                role="form" enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf
                                <div class="flex flex-col space-y-4" x-data="alpineFormData('{{ $issuePoint?->status }}')">

                                    <div>
                                        <x-input-label for="type" :value="__('ประเภท')" />
                                        <div class="mt-1 block w-full">{{ $issuePoint?->getTypeFormat() }}</div>
                                    </div>

                                    <div>
                                        <x-input-label for="slug" :value="__('Slug')" />
                                        <div class="mt-1 block w-full">{{ $issuePoint?->slug }}</div>
                                    </div>

                                    <div>
                                        <x-input-label for="name" :value="__('ชื่อ')" />
                                        <div class="mt-1 block w-full">{{ $issuePoint?->name }}</div>
                                    </div>

                                    <div>
                                        <x-input-label for="points" :value="__('จำนวนแต้ม')" />
                                        <div class="mt-1 block w-full">{{ $issuePoint?->points }}</div>
                                    </div>

                                    <div>
                                        <x-input-label for="desc" :value="__('รายละเอียดเพิ่มเติม')" />
                                        <div class="mt-1 block w-full">{{ $issuePoint?->desc }}</div>
                                    </div>

                                    @if (!$issuePoint->isApproved())
                                        <div>
                                            <x-input-label for="status" :value="__('สถานะ')" />
                                            <x-radio-input name="status" readonly class="mt-1 block w-full" required
                                                :selections="$issueStatusSelections" :selected="$issuePoint?->status" x-model="status" />
                                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                        </div>
                                    @else
                                        <div>
                                            <x-input-label for="desc" :value="__('สถานะ')" />
                                            <div class="mt-1 block w-full">{{ $issuePoint?->getStatusFormat() }}</div>
                                        </div>
                                    @endif

                                    @if (!$issuePoint->isApproved())
                                        <div class="flex items-center justify-start mt-4 gap-x-2">
                                            <button type="submit"
                                                class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
                                        </div>
                                    @endif
                                </div>

                                <x-slot name="javascript">
                                    <script type="text/javascript">
                                        function alpineFormData(status) {
                                            return {
                                                status: status,
                                            }
                                        }
                                    </script>
                                </x-slot>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-backend-layout>
