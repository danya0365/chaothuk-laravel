<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ขนส่ง & โลจิสติกส์
            'ขนส่งสินค้าทั่วไป',
            'ขนส่งสินค้าเกษตร',
            'ขนส่งวัสดุก่อสร้าง',
            'ขนย้ายบ้าน / สำนักงาน',
            'ขนส่งสินค้าอุตสาหกรรม',
            'ขนส่งสินค้าเย็น / แช่แข็ง',
            // ยานพาหนะ
            'รถกะบะรับจ้าง',
            'รถบรรทุก 6 ล้อ',
            'รถบรรทุก 10 ล้อ',
            'รถพ่วงบรรทุก',
            'รถตู้รับจ้าง',
            'มอเตอร์ไซค์รับจ้าง',
            // บริการเสริม
            'ช่างซ่อมรถ',
            'ล้างรถ & ดูแลรักษา',
            'ประกันภัยรถ',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
