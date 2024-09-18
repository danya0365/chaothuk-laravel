<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserType::truncate();
        UserType::create([
            'name' => 'ลูกค้าสินเชื่อ',
        ]);
        UserType::create([
            'name' => 'ลูกค้าเงินฝาก',
        ]);
    }
}
