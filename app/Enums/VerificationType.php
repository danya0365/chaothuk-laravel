<?php

namespace App\Enums;

use LaracraftTech\LaravelUsefulAdditions\Traits\UsefulEnums;

enum VerificationType: string
{
    use UsefulEnums;

    case PHONE = 'phone';
    case EMAIL = 'email';
    case ID_CARD = 'id_card';
    case DRIVING_LICENSE = 'driving_license';
    case VEHICLE_REGISTRATION = 'vehicle_registration';
    case CRIMINAL_RECORD = 'criminal_record';
    case BUSINESS_LICENSE = 'business_license';
}
