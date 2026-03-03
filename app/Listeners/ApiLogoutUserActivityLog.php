<?php

namespace App\Listeners;

use App\Enums\UserActivityType;
use App\Events\ApiLogout;
use App\Models\UserActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ApiLogoutUserActivityLog
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
    public function handle(ApiLogout $event): void
    {
        $user = $event->user;
        UserActivityLog::create(['user_id' => $user->id, 'activity_type' => UserActivityType::API_LOGOUT->value]);
    }
}