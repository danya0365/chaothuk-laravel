<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use App\Models\UserBackend;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => config('auth.supervisor.name'),
            'email' => config('auth.supervisor.email'),
            'password' => Hash::make(config('auth.supervisor.password')),
        ]);

        $user->roles()->sync(['role_id' => Role::SUPERVISOR->value]);
    }
}