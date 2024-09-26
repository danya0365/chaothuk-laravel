<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum MessengerConversationType: string
{
    use UsefulEnums;

    case TEXT = 'text';
    case IMAGE = 'image';
    case YOUTUBE = 'youtube';
    case URL = 'url';
    case EMOTICON = 'emoticon';
}
