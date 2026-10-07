<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkBookingFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['waiting-to-confirm', 'confirm', 'close', 'cancel'];

        return [
            'work_id' => Work::factory(),
            'author_id' => User::factory(),
            'customer_message' => $this->faker->randomElement([
                'ต้องการขนของย้ายบ้าน',
                'รับสินค้าจากโกดัง ส่งลูกค้า',
                'ขนวัสดุก่อสร้าง',
                'ส่งของด่วน',
                'ขนเฟอร์นิเจอร์',
            ]),
            'mobile_phone' => '0'.$this->faker->numerify('########'),
            'booking_date' => $this->faker->dateTimeBetween('-3 months', '+1 month'),
            'booking_status' => $this->faker->randomElement($statuses),
        ];
    }

    public function waitingToConfirm(): static
    {
        return $this->state(fn () => ['booking_status' => 'waiting-to-confirm']);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['booking_status' => 'confirm']);
    }

    public function closed(): static
    {
        return $this->state(fn () => ['booking_status' => 'close']);
    }
}
