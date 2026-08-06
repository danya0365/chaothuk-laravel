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
        // Title keys the vehicle type → work_type_id must match, or the mock
        // image (assigned by work_type_id) won't match the visible title.
        $thaiTitles = [
            'รถกะบะรับจ้าง ขนของ ย้ายบ้าน'      => 'รถกะบะ',
            'รถกะบะขนส่งสินค้าทั่วกรุงเทพ'      => 'รถกะบะ',
            'บริการรถกะบะรับจ้าง ราคาถูก'       => 'รถกะบะ',
            'รถบรรทุกรับจ้าง วิ่งต่างจังหวัด'     => 'รถบรรทุก',
            'รับจ้างขนส่งสินค้าทั่วกรุงเทพ'      => 'รถบรรทุก',
            'รถบรรทุกขนส่งสินค้าเกษตร'          => 'รถบรรทุก',
            'รถสิบล้อรับจ้าง ขนวัสดุก่อสร้าง'    => 'รถสิบล้อ',
            'สิบล้อรับจ้าง ขนส่งดินทิ้ง'         => 'รถสิบล้อ',
            'มอเตอร์ไซค์รับจ้างส่งของ'          => 'มอเตอร์ไซค์',
            'มอเตอร์ไซค์รับจ้าง ส่งของด่วน'     => 'มอเตอร์ไซค์',
        ];
        $title = $this->faker->randomElement(array_keys($thaiTitles));
        $workTypeTitle = $thaiTitles[$title];

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
            'work_type_id'     => WorkType::where('title', $workTypeTitle)->value('id') ?? 1,
            'code'             => strtoupper($this->faker->unique()->bothify('??-####')),
            'title'            => $title,
            'description'      => $this->faker->randomElement($descriptions),
            'price'            => $this->faker->numberBetween(500, 50000),
            // image fields assigned post-create by MockSeeder from the local
            // ComfyUI mock pool (see MockImageService) — empty here on purpose.
            'primary_image'    => null,
            'images'           => [],
            'avg_review_rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'like_count'       => $this->faker->numberBetween(0, 500),
            'reply_count'      => $this->faker->numberBetween(0, 50),
            'display_priority' => $this->faker->numberBetween(0, 100),
            'latitude'         => $this->faker->latitude(13.0, 19.5),
            'longitude'        => $this->faker->longitude(98.0, 104.5),
        ];
    }
}
