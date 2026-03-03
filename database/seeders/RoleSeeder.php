<?php

namespace Database\Seeders;

use App\Enums\Permission as EnumsPermission;
use App\Models\Permission;
use App\Enums\Role as EnumsRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ACCESS_BACKEND = Permission::where(['slug' => EnumsPermission::ACCESS_BACKEND->value, 'name' => EnumsPermission::ACCESS_BACKEND->value])->first();
        $MANAGE_PERMISSION = Permission::where(['slug' => EnumsPermission::MANAGE_PERMISSION->value, 'name' => EnumsPermission::MANAGE_PERMISSION->value])->first();
        $MANAGE_ROLE = Permission::where(['slug' => EnumsPermission::MANAGE_ROLE->value, 'name' => EnumsPermission::MANAGE_ROLE->value])->first();
        $CREATE_RECRUIT = Permission::where(['slug' => EnumsPermission::CREATE_RECRUIT->value, 'name' => EnumsPermission::CREATE_RECRUIT->value])->first();
        $CREATE_WORK = Permission::where(['slug' => EnumsPermission::CREATE_WORK->value, 'name' => EnumsPermission::CREATE_WORK->value])->first();
        $REVIEW_WORK = Permission::where(['slug' => EnumsPermission::REVIEW_WORK->value, 'name' => EnumsPermission::REVIEW_WORK->value])->first();
        $REPLY_REVIEW = Permission::where(['slug' => EnumsPermission::REPLY_REVIEW->value, 'name' => EnumsPermission::REPLY_REVIEW->value])->first();

        $SUPERVISOR = Role::create(['name' => __('common.role-' . EnumsRole::SUPERVISOR->value), 'id' => EnumsRole::SUPERVISOR->value]);
        $BACKEND = Role::create(['name' => __('common.role-' . EnumsRole::BACKEND->value), 'id' => EnumsRole::BACKEND->value]);
        $MEMBER = Role::create(['name' => __('common.role-' . EnumsRole::MEMBER->value), 'id' => EnumsRole::MEMBER->value]);
        $MOBILE_PHONE = Role::create(['name' => __('common.role-' . EnumsRole::MOBILE_PHONE->value), 'id' => EnumsRole::MOBILE_PHONE->value]);

        $SUPERVISOR->permissions()->sync([$ACCESS_BACKEND->id, $MANAGE_PERMISSION->id, $MANAGE_ROLE->id, $CREATE_RECRUIT->id, $CREATE_WORK->id, $REVIEW_WORK->id, $REPLY_REVIEW->id]);
        $BACKEND->permissions()->sync([$ACCESS_BACKEND->id, $CREATE_RECRUIT->id, $CREATE_WORK->id, $REVIEW_WORK->id, $REPLY_REVIEW->id]);
        $MEMBER->permissions()->sync([$CREATE_RECRUIT->id, $CREATE_WORK->id, $REVIEW_WORK->id, $REPLY_REVIEW->id]);
    }
}