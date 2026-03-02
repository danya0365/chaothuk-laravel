<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkBookingFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['pending', 'worker_confirmed', 'customer_confirmed', 'completed', 'cancelled'];

        return [
            'work_id'          => Work::factory(),
            'customer_id'      => User::factory(),
            'customer_message' => $this->faker->randomElement([
                'ต้องการขนของย้ายบ้าน',
                'รับสินค้าจากโกดัง ส่งลูกค้า',
                'ขนวัสดุก่อสร้าง',
                'ส่งของด่วน',
                'ขนเฟอร์นิเจอร์',
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
