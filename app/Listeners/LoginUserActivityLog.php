<?php

namespace App\Listeners;

use App\Enums\UserActivityType;
use App\Events\Login;
use App\Models\UserActivityLog;

class LoginUserActivityLog
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
    public function handle(Login $event): void
    {
        $user = $event->user;
        UserActivityLog::create(['user_id' => $user->id, 'activity_type' => UserActivityType::LOGIN->value]);
    }
}
