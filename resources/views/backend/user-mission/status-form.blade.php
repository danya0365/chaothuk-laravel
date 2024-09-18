<x-backend-layout>
    <x-slot name="header">
        @include('backend.user-mission.header')
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
                        href="{{ route('backend.user-missions.in-progress-list') }}">
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

                            <form method="POST"
                                action="{{ route('backend.user-missions.status-submit', $userMission->id) }}"
                                role="form" enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf

                                <div class="flex flex-col space-y-4" x-data="alpineFormData('{{ $userMission?->status }}')">

                                    <div>
                                        <x-input-label for="points" :value="__('แต้มที่จะได้รับ')" />
                                        <div class="mt-1 block w-full">{{ $userMission?->points }}</div>
                                    </div>

                                    @if (!$userMission->isComplete())
                                        <div>
                                            <x-input-label for="status" :value="__('สถานะ')" />
                                            <x-radio-input name="status" class="mt-1 block w-full" required
                                                :selections="$missionStatusSelections" :selected="$userMission?->status" x-model="status" />
                                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                        </div>

                                        <div>
                                            <div class="flex flex-col space-y-4">
                                                <h2>ข้อมูลสำหรับบันทึกการเปลี่ยนสถานะ</h2>
                                            </div>
                                            <div>
                                                <x-input-label for="note" :value="__('บันทึกเพิ่มเติม')" />
                                                <x-textarea id="note" name="note" class="mt-1 block w-full"
                                                    required>{!! old('note', $userMission?->note) !!}</x-textarea>
                                                <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                            </div>
                                        </div>
                                    @else
                                        <div>
                                            <x-input-label :value="__('สถานะ')" />
                                            <div class="mt-1 block w-full">{{ $userMission?->getMissionStatusFormat() }}
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label :value="__('บันทึกเพิ่มเติม')" />
                                            <div class="mt-1 block w-full">{{ $userMission?->note }}</div>
                                        </div>
                                    @endif

                                    @if (!$userMission->isComplete())
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
                                                status: status
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
