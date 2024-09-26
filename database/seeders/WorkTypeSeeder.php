<?php

namespace Database\Seeders;

use App\Enums\Configuration as EnumsConfiguration;
use App\Enums\ConfigurationValueType;
use App\Enums\ThemeScheme;
use App\Models\Configuration;
use App\Models\WorkType;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkType::truncate();
        WorkType::create([
            'title' => 'รถกะบะ',
        ]);
        WorkType::create([
            'title' => 'รถบรรทุก',
        ]);
        WorkType::create([
            'title' => 'รถสิบล้อ',
        ]);
        WorkType::create([
            'title' => 'มอเตอร์ไซค์',
        ]);
    }
}