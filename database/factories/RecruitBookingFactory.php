<?php

namespace Database\Factories;

use App\Models\Recruit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecruitBookingFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['pending', 'worker_confirmed', 'customer_confirmed', 'completed', 'cancelled'];

        return [
            'recruit_id'       => Recruit::factory(),
            'worker_id'        => User::factory(),
            'worker_message'   => $this->faker->randomElement([
                'ผมมีประสบการณ์ขับรถมา 5 ปี',
                'มีใบขับขี่ประเภท 2',
                'พร้อมเริ่มงานได้ทันที',
                'มีรถส่วนตัว สามารถใช้ได้',
                'ขยัน ซื่อสัตย์ ตรงเวลา',
            ]),
            'mobile_phone'     => '0' . $this->faker->numerify('########'),
            'booking_date'     => $this->faker->dateTimeBetween('-3 months', '+1 month'),
            'booking_status'   => $this->faker->randomElement($statuses),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['booking_status' => 'pending']);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => ['booking_status' => 'completed']);
    }
}
