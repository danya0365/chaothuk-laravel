<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — Entry point สำหรับ migrate:fresh --seed
 *
 * ─── SEEDER MAP ────────────────────────────────────────────────────────────
 *
 *  StarterSeeder        Master/reference data เท่านั้น (WorkType, Category)
 *                       รันอัตโนมัติเมื่อ migrate:fresh --seed
 *
 *  DemoSeeder           Demo accounts ที่รู้จัก (worker1, employer1 ฯลฯ)
 *                       Dev และ Staging — รันแยก
 *
 *  MockSeeder           ข้อมูล volume สูง (50 users, 100 works, 450 reviews)
 *                       Dev เท่านั้น — เรียก DemoSeeder อัตโนมัติภายใน
 *
 *  ProductionSeeder     Admin user สำหรับ Production
 *                       ดึง password จาก .env (ADMIN_EMAIL, ADMIN_PASSWORD)
 *
 * ─── PER-ENVIRONMENT COMMANDS ──────────────────────────────────────────────
 *
 *  [DEV]
 *    sail artisan migrate:fresh --seed
 *    sail artisan db:seed --class=MockSeeder    ← รัน DemoSeeder ภายในอัตโนมัติ
 *
 *  [STAGING]
 *    sail artisan migrate:fresh --seed
 *    sail artisan db:seed --class=DemoSeeder
 *
 *  [PRODUCTION]
 *    sail artisan migrate --force
 *    sail artisan db:seed --force               ← StarterSeeder เท่านั้น
 *    sail artisan db:seed --class=ProductionSeeder --force
 *
 * ─── SEEDER ORDER (ถ้ารันแยก) ──────────────────────────────────────────────
 *    1. DatabaseSeeder (StarterSeeder)  ← ต้องมาก่อนเสมอ
 *    2. DemoSeeder                      ← optional ก่อน Mock
 *    3. MockSeeder                      ← dev volume data
 *
 * ───────────────────────────────────────────────────────────────────────────
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Geography / Location (ลำดับสำคัญ — Province ต้องมาก่อน Work) ──
        $this->call([
            GeographySeeder::class,
            ProvinceSeeder::class,
            DistrictSeeder::class,
            SubDistrictSeeder::class,
        ]);

        // ─── Roles & Permissions ──────────────────────────────────────────────
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // ─── Config, Users, Reference Data ───────────────────────────────────
        $this->call([
            UserSeeder::class,
            ConfigurationSeeder::class,
        ]);

        // ─── Master Data (WorkTypes + Categories) ─────────────────────────────
        $this->call([
            WorkTypeSeeder::class,
            CategorySeeder::class,
        ]);
    }
}