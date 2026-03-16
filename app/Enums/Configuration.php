<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum Configuration: string
{
    use UsefulEnums;

    case COMPANY_NAME = 'company_name';
    case THEME_SCHEME = 'theme_scheme';
    
    // Platform Settings
    case SITE_NAME = 'site_name';
    case SITE_DESCRIPTION = 'site_description';
    case CONTACT_EMAIL = 'contact_email';
    case CONTACT_PHONE = 'contact_phone';
    case PLATFORM_FEE_PERCENT = 'platform_fee_percent';
    case MINIMUM_WITHDRAWAL = 'minimum_withdrawal';
    case FACEBOOK_URL = 'facebook_url';
    case LINE_URL = 'line_url';
}
