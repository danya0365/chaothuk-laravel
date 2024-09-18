<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'เจ้าของบริษัท', 'is_can_access_backend' => true, 'is_supervisor' => true]);
        Role::create(['name' => 'เจ้าหน้าที่บริษัท', 'is_can_access_backend' => true]);
        Role::create(['name' => 'ลูกค้า', 'is_customer' => true]);
        Role::create(['name' => 'ร้านค้า', 'is_merchant' => true]);
    }
}
