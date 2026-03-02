<?php

namespace Database\Factories;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'             => $this->faker->text(50),
            'content'           => json_encode(['message' => $this->faker->text(100)]),
            'notification_type' => NotificationType::GENERAL->value
        ];
    }
}
