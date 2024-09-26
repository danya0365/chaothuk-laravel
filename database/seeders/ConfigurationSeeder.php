<?php

namespace Database\Seeders;

use App\Enums\Configuration as EnumsConfiguration;
use App\Enums\ConfigurationValueType;
use App\Enums\ThemeScheme;
use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuration::truncate();
        Configuration::create([
            'slug' => EnumsConfiguration::COMPANY_NAME->value,
            'name' => 'ชื่อบริษัท',
            'value_type' => ConfigurationValueType::TEXT->value,
            'value' => 'บริษัทเช่าถูก จำกัด'
        ]);
        Configuration::create([
            'slug' => EnumsConfiguration::THEME_SCHEME->value,
            'name' => 'สีธีมของแอพ',
            'value_type' => ConfigurationValueType::OPTION->value,
            'value' => ThemeScheme::GREEN->value,
            'value_options' => ThemeScheme::values(),
        ]);
    }
}