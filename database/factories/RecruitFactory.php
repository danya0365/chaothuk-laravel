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
        $thaiTitles = [
            'ต้องการคนขับรถกะบะ ประจำบริษัท',
            'รับสมัครคนขับรถบรรทุก 10 ล้อ',
            'หาคนขับมอเตอร์ไซค์ส่งของ ด่วน',
            'ต้องการพนักงานขับรถตู้ส่วนตัว',
            'รับสมัครช่างซ่อมรถ มีประสบการณ์',
            'ต้องการคนช่วยขนของ รายวัน',
            'หาคนขับรถส่งสินค้า ทำงานเป็นกะ',
            'รับสมัครพนักงานโลจิสติกส์',
            'ต้องการผู้ช่วยในโกดัง',
            'หาคนขับรถพ่วงรถบรรทุก',
        ];

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
            'work_type_id'  => WorkType::inRandomOrder()->value('id') ?? 1,
            'title'         => $this->faker->randomElement($thaiTitles),
            'description'   => $this->faker->randomElement($descriptions),
            'budget'        => $this->faker->numberBetween(10000, 80000),
            'primary_image' => 'https://picsum.photos/seed/' . $this->faker->numberBetween(201, 400) . '/800/600',
            'images'        => json_encode([
                'https://picsum.photos/seed/' . $this->faker->numberBetween(201, 400) . '/800/600',
            ]),
            'recruit_status' => 'open',
        ];
    }
}
