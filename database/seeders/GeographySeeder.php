<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeographySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data() as $key => $row) {
            DB::table('geographies')->insert([
                'id' => $row['id'],
                'name' => $row['name'],
                'created_at' =>  \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
        }
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            ['id' => '1', 'name' => 'ภาคเหนือ'],
            ['id' => '2', 'name' => 'ภาคกลาง'],
            ['id' => '3', 'name' => 'ภาคตะวันออกเฉียงเหนือ'],
            ['id' => '4', 'name' => 'ภาคตะวันตก'],
            ['id' => '5', 'name' => 'ภาคตะวันออก'],
            ['id' => '6', 'name' => 'ภาคใต้'],
        ];
    }
}
