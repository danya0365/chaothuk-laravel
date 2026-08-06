<?php

namespace Database\Factories;

use App\Models\Province;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecruitFactory extends Factory
{
    public function definition(): array
    {
        // Title keys the vehicle type → work_type_id must match, or the mock
        // image (assigned by work_type_id) won't match the visible title.
        $thaiTitles = [
            'ต้องการคนขับรถกะบะ ประจำบริษัท'   => 'รถกะบะ',
            'รับสมัครคนขับรถกะบะ ขนของ'       => 'รถกะบะ',
            'รับสมัครคนขับรถบรรทุก 10 ล้อ'     => 'รถบรรทุก',
            'หาคนขับรถพ่วงรถบรรทุก'           => 'รถบรรทุก',
            'หาคนขับรถสิบล้อ ขนวัสดุ'          => 'รถสิบล้อ',
            'รับสมัครคนขับรถสิบล้อ'            => 'รถสิบล้อ',
            'หาคนขับมอเตอร์ไซค์ส่งของ ด่วน'    => 'มอเตอร์ไซค์',
            'รับสมัครไรเดอร์มอเตอร์ไซค์'       => 'มอเตอร์ไซค์',
            'ต้องการพนักงานขับรถตู้ส่วนตัว'    => 'รถกะบะ',
            'ต้องการผู้ช่วยในโกดัง'            => 'รถบรรทุก',
        ];
        $title = $this->faker->randomElement(array_keys($thaiTitles));
        $workTypeTitle = $thaiTitles[$title];

        $descriptions = [
            'ต้องการคนขับรถที่มีใบขับขี่ถูกต้อง มีประสบการณ์อย่างน้อย 2 ปี',
            'สวัสดิการดี มีประกันสุขภาพ เงินเดือนตามที่ตกลง',
            'ทำงานวันจันทร์-เสาร์ มีโอทีเพิ่มเติม',
            'ยินดีรับนักศึกษาจบใหม่ มีการอบรมให้',
            'งานรายวัน ค่าแรงดี จ่ายทุกวัน',
        ];

        return [
            'author_id'     => User::factory(),
            'province_id'   => Province::inRandomOrder()->value('id') ?? 1,
            'work_type_id'  => WorkType::where('title', $workTypeTitle)->value('id') ?? 1,
            'title'         => $title,
            'description'   => $this->faker->randomElement($descriptions),
            'budget'       => $this->faker->numberBetween(10000, 80000),
            // image fields assigned post-create by MockSeeder (local mock pool)
            'primary_image' => null,
            'images'        => [],
            'recruit_status' => $this->faker->randomElement(['stand-by', 'busy', 'close']),
            'latitude'       => $this->faker->latitude(13.0, 19.5),
            'longitude'      => $this->faker->longitude(98.0, 104.5),
        ];
    }
}
