<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum UserActivityType: string
{
    use UsefulEnums;

    case LOGIN = 'login';
    case OPEN_URL = 'open_url';
    case LOGOUT = 'logout';
    case API_LOGIN = 'api_login';
    case API_LOGOUT = 'api_logout';
}
