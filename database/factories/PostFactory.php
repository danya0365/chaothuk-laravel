<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $positiveReviews = [
            'งานดีมาก ตรงเวลา แนะนำเลยครับ',
            'บริการดีเยี่ยม ของถึงปลายทางครบ ไม่มีชิ้นไหนเสียหาย',
            'คนขับสุภาพมาก ทำงานเป็นมืออาชีพ',
            'ราคาสมเหตุสมผล งานออกมาสวย จะใช้อีกครั้งแน่นอน',
            'ขอบคุณมากครับ บริการดีมาก',
        ];

        $mediumReviews = [
            'งานพอใช้ได้ แต่มาช้ากว่านัดนิดหน่อย',
            'โอเคครับ ของถึงครบ แต่อาจต้องนัดชัดเจนกว่านี้',
            'บริการกลางๆ ไม่แย่แต่ก็ไม่ดีมาก',
        ];

        $allReviews = array_merge($positiveReviews, $positiveReviews, $mediumReviews);

        return [
            'author_id' => User::factory(),
            'title'     => $this->faker->optional(0.5)->randomElement([
                'รีวิวการใช้บริการ',
                'ประสบการณ์ใช้งาน',
                'คำแนะนำ',
                'รีวิวตรงๆ',
            ]),
            'content'   => $this->faker->randomElement($allReviews),
            'rating'    => $this->faker->randomElement([3, 4, 4, 5, 5, 5]),
            'images'    => json_encode([]),
            'parent_id' => null,
        ];
    }

    public function reply(): static
    {
        return $this->state(fn (array $attributes) => [
            'title'   => null,
            'rating'  => 0,
            'content' => $this->faker->randomElement([
                'ขอบคุณมากครับ ยินดีให้บริการอีกครั้ง',
                'ขอบคุณสำหรับรีวิวนะครับ',
                'ขอบคุณที่ใช้บริการครับ',
                'ยินดีครับ ขอบคุณที่ไว้วางใจ',
            ]),
            // parent_id must be provided via create(['parent_id' => $postId])
        ]);
    }
}
