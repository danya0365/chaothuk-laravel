<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Recruit;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DemoSeeder — ข้อมูลตัวอย่างสำหรับ development / staging
 *
 * Demo Accounts (password: password):
 *  - worker1@chaothuk.test  (ผู้รับงาน 1)
 *  - worker2@chaothuk.test  (ผู้รับงาน 2)
 *  - employer1@chaothuk.test (ผู้จ้าง 1)
 *  - employer2@chaothuk.test (ผู้จ้าง 2)
 *
 * Demo Works + Recruits (เพื่อทดสอบ UI)
 *
 * ต้องรัน StarterSeeder ก่อน (ต้องการ WorkType + Category อยู่แล้ว)
 *
 * Run: sail artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->toArray() ?: [1];

        // ─── Demo Users ───────────────────────────────────────────────────────
        $worker1 = User::firstOrCreate(
            ['email' => 'worker1@chaothuk.test'],
            [
                'name'              => 'สมชาย ขับรถดี',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'first_name'        => 'สมชาย',
                'last_name'         => 'ขับรถดี',
                'location'          => 'กรุงเทพมหานคร',
                'profile_image'     => 'https://i.pravatar.cc/150?img=11',
            ]
        );

        $worker2 = User::firstOrCreate(
            ['email' => 'worker2@chaothuk.test'],
            [
                'name'              => 'มานะ ทำงานดี',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'first_name'        => 'มานะ',
                'last_name'         => 'ทำงานดี',
                'location'          => 'เชียงใหม่',
                'profile_image'     => 'https://i.pravatar.cc/150?img=12',
            ]
        );

        $employer1 = User::firstOrCreate(
            ['email' => 'employer1@chaothuk.test'],
            [
                'name'              => 'บริษัท ขนส่งไทย จำกัด',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'first_name'        => 'ขนส่งไทย',
                'last_name'         => 'บริษัท',
                'location'          => 'กรุงเทพมหานคร',
                'profile_image'     => 'https://i.pravatar.cc/150?img=20',
            ]
        );

        $employer2 = User::firstOrCreate(
            ['email' => 'employer2@chaothuk.test'],
            [
                'name'              => 'ห้างหุ้นส่วน โลจิสติกส์ดี',
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'first_name'        => 'โลจิสติกส์ดี',
                'last_name'         => 'ห้างหุ้นส่วน',
                'location'          => 'นนทบุรี',
                'profile_image'     => 'https://i.pravatar.cc/150?img=21',
            ]
        );

        // ─── Demo Works ───────────────────────────────────────────────────────
        $workData = [
            [
                'author_id'   => $worker1->id,
                'title'       => 'รถกะบะรับจ้างขนส่ง กรุงเทพ-ปริมณฑล',
                'description' => 'ให้บริการขนส่งสินค้าทุกประเภท รวดเร็ว ตรงเวลา ราคาสมเหตุสมผล',
                'price'       => 1500,
                'img_seed'    => 10,
                'latitude'    => 13.7563,  // กรุงเทพ
                'longitude'   => 100.5018,
            ],
            [
                'author_id'   => $worker1->id,
                'title'       => 'รับจ้างขนย้ายบ้าน ครบวงจร',
                'description' => 'ย้ายบ้านครบชุด มีทีมงาน 3 คน รถกะบะ 1 คัน บริการครบ',
                'price'       => 3500,
                'img_seed'    => 20,
                'latitude'    => 13.8451,  // ดอนเมือง
                'longitude'   => 100.5681,
            ],
            [
                'author_id'   => $worker2->id,
                'title'       => 'รถบรรทุก 6 ล้อ วิ่งต่างจังหวัด',
                'description' => 'รับงานขนส่งสินค้าต่างจังหวัด สินค้าปลอดภัย จัดส่งรวดเร็ว',
                'price'       => 5000,
                'img_seed'    => 30,
                'latitude'    => 18.7883,  // เชียงใหม่
                'longitude'   => 98.9853,
            ],
            [
                'author_id'   => $worker2->id,
                'title'       => 'มอเตอร์ไซค์รับส่งของด่วน ในกรุงเทพ',
                'description' => 'ส่งของด่วนในกรุงเทพ ระยะเวลา 1 ชั่วโมงถึงปลายทาง',
                'price'       => 200,
                'img_seed'    => 40,
                'latitude'    => 13.7248,  // สีลม
                'longitude'   => 100.5230,
            ],
        ];

        foreach ($workData as $data) {
            $work = Work::firstOrCreate(
                ['title' => $data['title'], 'author_id' => $data['author_id']],
                [
                    'author_id'        => $data['author_id'],
                    'title'            => $data['title'],
                    'description'      => $data['description'],
                    'price'            => $data['price'],
                    'code'             => strtoupper(substr(md5($data['title']), 0, 8)),
                    'province_id'      => 1,
                    'work_type_id'     => 1,
                    'primary_image'    => 'https://picsum.photos/seed/' . $data['img_seed'] . '/800/600',
                    'avg_review_rating' => 0,
                    'latitude'         => $data['latitude'],
                    'longitude'        => $data['longitude'],
                ]
            );
            $work->categories()->sync(array_slice($categoryIds, 0, 2));
        }

        // ─── Demo Recruits ────────────────────────────────────────────────────
        $recruitData = [
            [
                'author_id'   => $employer1->id,
                'title'       => 'ต้องการคนขับรถบรรทุก มีประสบการณ์',
                'description' => 'รับสมัครคนขับรถบรรทุก 10 ล้อ มีใบขับขี่ประเภท 2 สวัสดิการดี',
                'budget'      => 25000,
                'img_seed'    => 50,
                'latitude'    => 13.6900,  // สมุทรปราการ
                'longitude'   => 100.7501,
            ],
            [
                'author_id'   => $employer2->id,
                'title'       => 'หาพนักงานขับรถตู้ส่วนตัว ประจำ',
                'description' => 'ต้องการพนักงานขับรถตู้ มีอาหาร สวัสดิการครบ',
                'budget'      => 18000,
                'img_seed'    => 60,
                'latitude'    => 13.8621,  // นนทบุรี
                'longitude'   => 100.5144,
            ],
        ];

        foreach ($recruitData as $data) {
            $recruit = Recruit::firstOrCreate(
                ['title' => $data['title'], 'author_id' => $data['author_id']],
                [
                    'author_id'      => $data['author_id'],
                    'title'          => $data['title'],
                    'description'    => $data['description'],
                    'budget'         => $data['budget'],
                    'province_id'    => 1,
                    'work_type_id'   => 1,
                    'primary_image'  => 'https://picsum.photos/seed/' . $data['img_seed'] . '/800/600',
                    'recruit_status' => 'stand-by',
                    'latitude'       => $data['latitude'],
                    'longitude'      => $data['longitude'],
                ]
            );
            $recruit->categories()->sync(array_slice($categoryIds, 0, 2));
        }

        $this->command->info('✅ DemoSeeder complete: 4 demo users, 4 works, 2 recruits');
    }
}
