<?php

namespace Database\Seeders;

use App\Enums\NotificationType;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::truncate();
        Notification::factory()
            ->count(500)
            ->sequence(fn ($sequence) => [
                'title' => 'แจ้งเตือน ' . $sequence->index + 1,
                'content' => 'ข้อความการแจ้งเตือน ' . $sequence->index + 1,
                'notification_type' => NotificationType::ANNOUNCEMENT->value
            ])
            ->create();
    }
}
