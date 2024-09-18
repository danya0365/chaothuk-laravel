<div class="flex flex-col space-y-4" x-data="alpineFormData('{{ $userMission?->status }}')">

    <x-text-input id="user_id" name="user_id" :value="old('user_id', $userMission?->customer_id ?? $customerId)" type="hidden" class="mt-1 block w-full" required />
    <x-text-input id="banner_promotion_id" name="banner_promotion_id" :value="old('banner_promotion_id', $userMission?->banner_promotion_id ?? $bannerPromotionId)" type="hidden"
        class="mt-1 block w-full" required />

    <div>
        <x-input-label for="points" :value="__('แต้มที่จะได้รับ')" />
        <x-text-input id="points" name="points" :value="old('points', $userMission?->points)" type="text"
            class="mt-1 block w-full @error('points')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('points')" class="mt-2" />
    </div>

    <div class="hidden">
        <x-input-label for="status" :value="__('สถานะ')" />
        <x-radio-input name="status" class="mt-1 block w-full" readonly required :selections="$missionStatusSelections" :selected="$userMission?->status"
            x-model="status" />
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <template x-if="isShowNote()">
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
    </template>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>

</div>

<x-slot name="javascript">
    <script type="text/javascript">
        function alpineFormData(status) {
            return {
                status: status,
                isShowNote() {
                    return this.status !== '{{ App\Enums\MissionStatus::IN_PROGRESS->value }}' && this.status !== ''
                },
            }
        }
    </script>
</x-slot>
