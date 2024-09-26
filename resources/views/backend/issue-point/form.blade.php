<div class="flex flex-col space-y-4" x-data="alpineFormData('{{ old('type', $issuePoint?->type) }}', '{{ old('repeat_type', $issuePoint?->cron()->getRepeatType()) }}', '{{ old('repeat_value', $issuePoint?->cron()->getRepeatValue()) }}')">

    <x-text-input id="user_id" name="user_id" :value="old('user_id', $issuePoint?->user_id ?? $memberId)" type="hidden" class="mt-1 block w-full" required />

    <div>
        <x-input-label for="slug" :value="__('Slug')" />
        <x-text-input id="slug" name="slug" :value="old('slug', $issuePoint?->slug)" type="text"
            class="mt-1 block w-full @error('slug')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" :value="__('ชื่อ')" />
        <x-text-input id="name" name="name" :value="old('name', $issuePoint?->name)" type="text"
            class="mt-1 block w-full @error('name')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="desc" :value="__('รายละเอียดเพิ่มเติม')" />
        <x-textarea id="desc" name="desc" class="mt-1 block w-full"
            required>{!! old('desc', $issuePoint?->desc) !!}</x-textarea>
        <x-input-error :messages="$errors->get('desc')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="points" :value="__('จำนวนแต้ม')" />
        <x-text-input id="points" name="points" :value="old('points', $issuePoint?->points)" type="text"
            class="mt-1 block w-full @error('points')
is-invalid
@enderror" required />
        <x-input-error :messages="$errors->get('points')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" :value="__('ประเภท')" />
        <x-radio-input x-model="issueType" name="type" class="mt-1 block w-full" required :selections="$issuePointTypeSelections"
            :selected="old('type', $issuePoint?->type)" />
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>


    <template x-if="isRepeat()">
        <div class="flex flex-col space-y-4" x-init="$nextTick(() => {
            bindDatepicker()
        })">
            <h2>ข้อมูลสำหรับการทำซ้ำ</h2>

            <div>
                <x-input-label for="start_at" :value="__('เริ่มวันที่')" />
                <x-text-input id="start_at" name="start_at" type="text" class="mt-1 block w-full datetime" required
                    :value="old('start_at', $issuePoint?->start_at)" />
                <x-input-error :messages="$errors->get('start_at')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="end_at" :value="__('สิ้นสุดวันที่')" />
                <x-text-input id="end_at" name="end_at" type="text" class="mt-1 block w-full datetime" required
                    :value="old('end_at', $issuePoint?->end_at)" />
                <x-input-error :messages="$errors->get('end_at')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="repeat_type" :value="__('ประเภทการทำซ้ำ')" />
                <x-radio-input name="repeat_type" class="mt-1 block w-full" required :selections="$cronRepeatTypeSelections"
                    :selected="old('repeat_type', $issuePoint?->cron()->getRepeatType())" x-model="repeatType" />
                <x-input-error :messages="$errors->get('repeat_type')" class="mt-2" />
            </div>

            <template x-if="isRepeatTypeWeekDayInWeek()">
                <div>
                    <x-input-label for="repeat_value" :value="__('ทุกวันของสัปดาห์')" />
                    <x-radio-input name="repeat_value" class="mt-1 block w-full" required :selections="$weekDaySelections"
                        :selected="old('repeat_value', $issuePoint?->cron()->getRepeatValue())" />
                    <x-input-error :messages="$errors->get('repeat_value')" class="mt-2" />
                </div>
            </template>
            <template x-if="isRepeatTypeDateInMonth()">
                <div>
                    <x-input-label for="repeat_value" :value="__('ทุกวันที่ของเดือน')" />
                    <x-radio-input name="repeat_value" class="mt-1 block w-full" required :selections="$dateSelections"
                        :selected="old('repeat_value', $issuePoint?->cron()->getRepeatValue())" />
                    <x-input-error :messages="$errors->get('repeat_value')" class="mt-2" />
                </div>
            </template>

        </div>
    </template>

    <div class="flex items-center justify-start mt-4 gap-x-2">
        <button type="submit"
            class="px-6 py-2 text-sm font-semibold rounded-md shadow-md text-green-100 bg-green-500 hover:bg-green-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300">Submit</button>
    </div>

</div>

<x-slot name="javascript">
    <script type="text/javascript">
        function alpineFormData(issueType, repeatType, repeatValue) {
            return {
                issueType: issueType,
                repeatType: repeatType,
                repeatValue: repeatValue,
                isRepeat() {
                    return this.issueType === '{{ \App\Enums\IssueType::REPEAT->value }}'
                },
                isNotEmptyRepeatType() {
                    return this.repeatType != ''
                },
                isRepeatTypeWeekDayInWeek() {
                    return this.repeatType === '{{ \App\Enums\CronRepeatType::AT_WEEKDAY_IN_WEEK->value }}'
                },
                isRepeatTypeDateInMonth() {
                    return this.repeatType === '{{ \App\Enums\CronRepeatType::AT_DATE_IN_MONTH->value }}'
                },
                bindDatepicker() {
                    flatpickr(".datetime", {
                        enableTime: false,
                        altInput: true,
                        altFormat: "l j F Y",
                    });
                }
            }
        }
    </script>
</x-slot>
