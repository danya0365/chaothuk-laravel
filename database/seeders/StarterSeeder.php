<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * StarterSeeder — ข้อมูล reference ที่จำเป็นสำหรับ production
 *
 * ประกอบด้วย:
 *  - Work categories (หมวดหมู่งาน)
 *  - Work types (ประเภทยานพาหนะ)
 *
 * ไม่มีข้อมูล demo / users / works ใดๆ ทั้งสิ้น
 *
 * Run: sail artisan db:seed --class=StarterSeeder
 */
class StarterSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WorkTypeSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
