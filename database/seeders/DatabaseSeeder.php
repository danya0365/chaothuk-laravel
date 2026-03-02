<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Default (production-safe reference data only):
     *   sail artisan migrate:fresh --seed
     *
     * Demo data (dev/staging):
     *   sail artisan db:seed --class=DemoSeeder
     *
     * Mock/test data (large volume):
     *   sail artisan db:seed --class=MockSeeder
     */
    public function run(): void
    {
        $this->call([
            GeographySeeder::class,
            ProvinceSeeder::class,
            DistrictSeeder::class,
            SubDistrictSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ConfigurationSeeder::class,
            WorkTypeSeeder::class,
            CategorySeeder::class,
        ]);
    }
}