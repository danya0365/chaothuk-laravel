<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'หมวด 1']);
        Category::create(['name' => 'หมวด 2']);
        Category::create(['name' => 'หมวด 3']);
        Category::create(['name' => 'หมวด 4']);
        Category::create(['name' => 'หมวด 5']);
    }
}
