<?php

namespace App\Listeners;

use App\Enums\UserActivityType;
use App\Events\ApiLogin;
use App\Models\UserActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ApiLoginUserActivityLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApiLogin $event): void
    {
        $user = $event->user;
        UserActivityLog::create(['user_id' => $user->id, 'activity_type' => UserActivityType::API_LOGIN->value]);
    }
}
