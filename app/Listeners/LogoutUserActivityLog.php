<?php

namespace App\Listeners;

use App\Enums\UserActivityType;
use App\Events\Logout;
use App\Models\UserActivityLog;

class LogoutUserActivityLog
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
    public function handle(Logout $event): void
    {
        $user = $event->user;
        UserActivityLog::create(['user_id' => $user->id, 'activity_type' => UserActivityType::LOGOUT->value]);
    }
}
