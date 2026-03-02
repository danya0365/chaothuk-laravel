<?php

namespace Database\Factories;

use App\Models\Province;
use App\Models\User;
use App\Models\WorkType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory
{
    public function definition(): array
    {
        $thaiTitles = [
            'รับจ้างขนส่งสินค้าทั่วกรุงเทพ',
            'รถกะบะรับจ้าง ขนของ ย้ายบ้าน',
            'บริการรถขนส่ง ราคาถูก',
            'รถบรรทุกรับจ้าง วิ่งต่างจังหวัด',
            'ขนส่งสินค้าด่วน ทั่วประเทศ',
            'รับจ้างขนย้ายสำนักงาน',
            'รถสิบล้อรับจ้าง ขนวัสดุก่อสร้าง',
            'มอเตอร์ไซค์รับจ้างส่งของ',
            'รถตู้รับจ้าง ทัวร์ส่วนตัว',
            'รับจ้างขนส่งสินค้าเกษตร',
        ];

        $descriptions = [
            'บริการขนส่งสินค้าทุกประเภท ราคาสมเหตุสมผล มีประสบการณ์มากกว่า 10 ปี',
            'รถสภาพดี คนขับมีประสบการณ์ ตรงเวลา ไม่ทิ้งงาน',
            'ให้บริการขนส่งทั่วประเทศ สินค้าก็ถึงปลายทางอย่างปลอดภัย',
            'บริการครบวงจร ทั้งรับของ ขนส่ง และส่งมอบ',
            'ราคาถูก บริการดี มีใบอนุญาตถูกต้องตามกฎหมาย',
        ];

        return [
            'author_id'        => User::factory(),
            'province_id'      => Province::inRandomOrder()->value('id') ?? 1,
            'work_type_id'     => WorkType::inRandomOrder()->value('id') ?? 1,
            'code'             => strtoupper($this->faker->unique()->bothify('??-####')),
            'title'            => $this->faker->randomElement($thaiTitles),
            'description'      => $this->faker->randomElement($descriptions),
            'price'            => $this->faker->numberBetween(500, 50000),
            'primary_image'    => 'https://picsum.photos/seed/' . $this->faker->numberBetween(1, 200) . '/800/600',
            'images'           => json_encode([
                'https://picsum.photos/seed/' . $this->faker->numberBetween(1, 200) . '/800/600',
                'https://picsum.photos/seed/' . $this->faker->numberBetween(1, 200) . '/800/600',
            ]),
            'avg_review_rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'like_count'       => $this->faker->numberBetween(0, 500),
            'reply_count'      => $this->faker->numberBetween(0, 50),
            'display_priority' => $this->faker->numberBetween(0, 100),
        ];
    }
}
