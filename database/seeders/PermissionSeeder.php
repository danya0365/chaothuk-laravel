<?php

namespace Database\Seeders;

use App\Enums\Permission as EnumsPermission;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['slug' => EnumsPermission::ACCESS_BACKEND->value, 'name' => EnumsPermission::ACCESS_BACKEND->value]);
        Permission::create(['slug' => EnumsPermission::MANAGE_PERMISSION->value, 'name' => EnumsPermission::MANAGE_PERMISSION->value]);
        Permission::create(['slug' => EnumsPermission::MANAGE_ROLE->value, 'name' => EnumsPermission::MANAGE_ROLE->value]);
        Permission::create(['slug' => EnumsPermission::CREATE_RECRUIT->value, 'name' => EnumsPermission::CREATE_RECRUIT->value]);
        Permission::create(['slug' => EnumsPermission::CREATE_WORK->value, 'name' => EnumsPermission::CREATE_WORK->value]);
        Permission::create(['slug' => EnumsPermission::REVIEW_WORK->value, 'name' => EnumsPermission::REVIEW_WORK->value]);
        Permission::create(['slug' => EnumsPermission::REPLY_REVIEW->value, 'name' => EnumsPermission::REPLY_REVIEW->value]);
    }
}