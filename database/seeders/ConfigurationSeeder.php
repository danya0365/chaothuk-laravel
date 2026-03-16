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
            'group' => 'general'
        ]);

        // Platform Settings
        $platformSettings = [
            [
                'slug' => EnumsConfiguration::SITE_NAME->value,
                'name' => 'ชื่อเว็บไซต์ (Site Name)',
                'value_type' => ConfigurationValueType::TEXT->value,
                'value' => 'Chaothuk',
                'group' => 'general'
            ],
            [
                'slug' => EnumsConfiguration::SITE_DESCRIPTION->value,
                'name' => 'รายละเอียดเว็บไซต์',
                'value_type' => ConfigurationValueType::TEXTAREA->value,
                'value' => 'แพลตฟอร์มเช่าของและจ้างงานที่ใหญ่ที่สุด',
                'group' => 'general'
            ],
            [
                'slug' => EnumsConfiguration::CONTACT_EMAIL->value,
                'name' => 'อีเมลติดต่อระดับแพลตฟอร์ม',
                'value_type' => ConfigurationValueType::TEXT->value,
                'value' => 'contact@chaothuk.com',
                'group' => 'general'
            ],
            [
                'slug' => EnumsConfiguration::CONTACT_PHONE->value,
                'name' => 'เบอร์โทรศัพท์ (Help Center)',
                'value_type' => ConfigurationValueType::TEXT->value,
                'value' => '02-123-4567',
                'group' => 'social'
            ],
            [
                'slug' => EnumsConfiguration::FACEBOOK_URL->value,
                'name' => 'Facebook Page URL',
                'value_type' => ConfigurationValueType::URL->value,
                'value' => 'https://facebook.com/chaothuk',
                'group' => 'social'
            ],
            [
                'slug' => EnumsConfiguration::LINE_URL->value,
                'name' => 'Line Official Account URL',
                'value_type' => ConfigurationValueType::URL->value,
                'value' => 'https://line.me/R/ti/p/@chaothuk',
                'group' => 'social'
            ],
            [
                'slug' => EnumsConfiguration::PLATFORM_FEE_PERCENT->value,
                'name' => 'เปอร์เซ็นต์หักบัญชีแพลตฟอร์ม (%)',
                'value_type' => ConfigurationValueType::TEXT->value,
                'value' => '10',
                'group' => 'payment'
            ],
            [
                'slug' => EnumsConfiguration::MINIMUM_WITHDRAWAL->value,
                'name' => 'ขั้นต่ำในการถอนเงิน (บาท)',
                'value_type' => ConfigurationValueType::TEXT->value,
                'value' => '500',
                'group' => 'payment'
            ]
        ];

        foreach ($platformSettings as $setting) {
            Configuration::create($setting);
        }
    }
}