<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum ConfigurationValueType: string
{
    use UsefulEnums;

    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case OPTION = 'option';
    case BOOLEAN = 'boolean';
    case URL = 'url';
}
