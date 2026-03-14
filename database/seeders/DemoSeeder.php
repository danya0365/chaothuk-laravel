<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dispute;
use App\Models\Favorite;
use App\Models\Portfolio;
use App\Models\Recruit;
use App\Models\SessionLocationLog;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkAvailability;
use App\Models\WorkBlockedDate;
use App\Models\WorkBooking;
use App\Models\WorkSession;
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
                'latitude'    => 13.7563,
                'longitude'   => 100.5018,
            ],
            [
                'author_id'   => $worker1->id,
                'title'       => 'รับจ้างขนย้ายบ้าน ครบวงจร',
                'description' => 'ย้ายบ้านครบชุด มีทีมงาน 3 คน รถกะบะ 1 คัน บริการครบ',
                'price'       => 3500,
                'img_seed'    => 20,
                'latitude'    => 13.8451,
                'longitude'   => 100.5681,
            ],
            [
                'author_id'   => $worker2->id,
                'title'       => 'รถบรรทุก 6 ล้อ วิ่งต่างจังหวัด',
                'description' => 'รับงานขนส่งสินค้าต่างจังหวัด สินค้าปลอดภัย จัดส่งรวดเร็ว',
                'price'       => 5000,
                'img_seed'    => 30,
                'latitude'    => 18.7883,
                'longitude'   => 98.9853,
            ],
            [
                'author_id'   => $worker2->id,
                'title'       => 'มอเตอร์ไซค์รับส่งของด่วน ในกรุงเทพ',
                'description' => 'ส่งของด่วนในกรุงเทพ ระยะเวลา 1 ชั่วโมงถึงปลายทาง',
                'price'       => 200,
                'img_seed'    => 40,
                'latitude'    => 13.7248,
                'longitude'   => 100.5230,
            ],
        ];

        $demoWorkIds = [];
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
            $demoWorkIds[] = $work->id;
        }

        // ─── Demo Recruits ────────────────────────────────────────────────────
        $recruitData = [
            [
                'author_id'   => $employer1->id,
                'title'       => 'ต้องการคนขับรถบรรทุก มีประสบการณ์',
                'description' => 'รับสมัครคนขับรถบรรทุก 10 ล้อ มีใบขับขี่ประเภท 2 สวัสดิการดี',
                'budget'      => 25000,
                'img_seed'    => 50,
                'latitude'    => 13.6900,
                'longitude'   => 100.7501,
            ],
            [
                'author_id'   => $employer2->id,
                'title'       => 'หาพนักงานขับรถตู้ส่วนตัว ประจำ',
                'description' => 'ต้องการพนักงานขับรถตู้ มีอาหาร สวัสดิการครบ',
                'budget'      => 18000,
                'img_seed'    => 60,
                'latitude'    => 13.8621,
                'longitude'   => 100.5144,
            ],
        ];

        $demoRecruitIds = [];
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
            $demoRecruitIds[] = $recruit->id;
        }

        // =====================================================================
        // Worker1 extras: availabilities, favorites, portfolios, sessions, disputes
        // =====================================================================
        $w1Works = Work::where('author_id', $worker1->id)->pluck('id')->toArray();

        // ─── Work Availabilities (worker1's works) ────────────────────────────
        foreach ($w1Works as $wId) {
            foreach ([1, 2, 3, 4, 5] as $day) { // Mon-Fri
                WorkAvailability::firstOrCreate(
                    ['work_id' => $wId, 'day_of_week' => $day, 'start_time' => '08:00'],
                    ['end_time' => '17:00', 'is_available' => true]
                );
            }
            // Saturday half day
            WorkAvailability::firstOrCreate(
                ['work_id' => $wId, 'day_of_week' => 6, 'start_time' => '09:00'],
                ['end_time' => '13:00', 'is_available' => true]
            );
            // Blocked date
            WorkBlockedDate::firstOrCreate(
                ['work_id' => $wId, 'blocked_date' => now()->addDays(7)->toDateString()],
                ['reason' => 'ลาพักร้อน']
            );
            WorkBlockedDate::firstOrCreate(
                ['work_id' => $wId, 'blocked_date' => now()->addDays(14)->toDateString()],
                ['reason' => 'วันหยุดนักขัตฤกษ์']
            );
        }

        // ─── Favorites (worker1 favorites some recruits + works) ──────────────
        if (!empty($demoRecruitIds)) {
            Favorite::firstOrCreate([
                'user_id'          => $worker1->id,
                'favoritable_type' => Recruit::class,
                'favoritable_id'   => $demoRecruitIds[0],
            ]);
        }
        if (count($demoWorkIds) >= 3) {
            Favorite::firstOrCreate([
                'user_id'          => $worker1->id,
                'favoritable_type' => Work::class,
                'favoritable_id'   => $demoWorkIds[2], // worker2's work
            ]);
        }
        // employer1 favorites worker1's work
        if (!empty($demoWorkIds)) {
            Favorite::firstOrCreate([
                'user_id'          => $employer1->id,
                'favoritable_type' => Work::class,
                'favoritable_id'   => $demoWorkIds[0],
            ]);
        }

        // ─── Portfolios (worker1 = 3 portfolios) ─────────────────────────────
        $workTypeId = \DB::table('work_types')->value('id') ?: 1;
        $portfolios = [
            ['title' => 'ขนส่งเฟอร์นิเจอร์ทั้งชุด', 'desc' => 'ขนย้ายเฟอร์นิเจอร์จากลาดพร้าวไปนนทบุรี ลูกค้าพอใจมาก ของไม่เสียหาย'],
            ['title' => 'ขนย้ายออฟฟิศ 3 ชั้น',    'desc' => 'ขนย้ายอุปกรณ์สำนักงาน คอมพิวเตอร์ โต๊ะ เก้าอี้ ครบขบวน เสร็จใน 1 วัน'],
            ['title' => 'ส่งสินค้าออนไลน์รายวัน',   'desc' => 'รับส่งพัสดุ 50-100 ชิ้น/วัน ครอบคลุมกรุงเทพ-ปริมณฑล'],
        ];
        foreach ($portfolios as $i => $p) {
            Portfolio::firstOrCreate(
                ['user_id' => $worker1->id, 'title' => $p['title']],
                [
                    'description'  => $p['desc'],
                    'images'       => [
                        'https://picsum.photos/seed/demo-port-' . $i . 'a/800/600',
                        'https://picsum.photos/seed/demo-port-' . $i . 'b/800/600',
                        'https://picsum.photos/seed/demo-port-' . $i . 'c/800/600',
                    ],
                    'work_type_id' => $workTypeId,
                ]
            );
        }

        // ─── Work Sessions (worker1 = 3 sessions) ────────────────────────────
        // Session 1: completed
        $session1 = WorkSession::firstOrCreate(
            ['worker_id' => $worker1->id, 'customer_id' => $employer1->id, 'started_at' => now()->subDays(5)->setHour(9)],
            [
                'sessionable_type'       => Work::class,
                'sessionable_id'         => $demoWorkIds[0] ?? 1,
                'started_at'             => now()->subDays(5)->setHour(9),
                'ended_at'               => now()->subDays(5)->setHour(14),
                'total_duration_minutes'  => 300,
                'price_agreed'            => 2500,
                'status'                  => 'completed',
                'worker_confirm'          => 'confirmed',
                'customer_confirm'        => 'confirmed',
                'notes'                   => 'ขนส่งเฟอร์นิเจอร์ลาดพร้าว-นนทบุรี เสร็จเรียบร้อย',
            ]
        );

        // GPS logs for session 1 (route ลาดพร้าว → นนทบุรี)
        $s1Route = [
            [13.8000, 100.5700], [13.8050, 100.5650], [13.8120, 100.5580],
            [13.8200, 100.5500], [13.8280, 100.5420], [13.8350, 100.5330],
            [13.8430, 100.5250], [13.8500, 100.5180],
        ];
        foreach ($s1Route as $idx => $pt) {
            SessionLocationLog::firstOrCreate([
                'session_id'  => $session1->id,
                'user_id'     => $worker1->id,
                'recorded_at' => now()->subDays(5)->setHour(9)->addMinutes($idx * 35),
            ], [
                'latitude'  => $pt[0],
                'longitude' => $pt[1],
                'accuracy'  => rand(5, 20),
                'speed'     => rand(20, 60) / 10,
                'heading'   => rand(300, 360),
            ]);
        }

        // Session 2: active (ongoing right now)
        $session2 = WorkSession::firstOrCreate(
            ['worker_id' => $worker1->id, 'customer_id' => $employer2->id, 'started_at' => now()->subHours(2)],
            [
                'sessionable_type'       => Work::class,
                'sessionable_id'         => $demoWorkIds[1] ?? 1,
                'started_at'             => now()->subHours(2),
                'ended_at'               => null,
                'total_duration_minutes'  => 0,
                'price_agreed'            => 3500,
                'status'                  => 'active',
                'worker_confirm'          => 'confirmed',
                'customer_confirm'        => 'pending',
                'notes'                   => 'ขนย้ายบ้านจากบางนาไปรังสิต',
            ]
        );

        // GPS logs for active session 2
        $s2Route = [
            [13.6670, 100.6040], [13.6750, 100.5980], [13.6850, 100.5900],
            [13.7000, 100.5800], [13.7150, 100.5700], [13.7350, 100.5600],
        ];
        foreach ($s2Route as $idx => $pt) {
            SessionLocationLog::firstOrCreate([
                'session_id'  => $session2->id,
                'user_id'     => $worker1->id,
                'recorded_at' => now()->subHours(2)->addMinutes($idx * 20),
            ], [
                'latitude'  => $pt[0],
                'longitude' => $pt[1],
                'accuracy'  => rand(5, 15),
                'speed'     => rand(30, 70) / 10,
                'heading'   => rand(330, 360),
            ]);
        }

        // Session 3: completed + cancelled
        WorkSession::firstOrCreate(
            ['worker_id' => $worker1->id, 'customer_id' => $employer1->id, 'started_at' => now()->subDays(10)->setHour(8)],
            [
                'sessionable_type'       => Work::class,
                'sessionable_id'         => $demoWorkIds[0] ?? 1,
                'started_at'             => now()->subDays(10)->setHour(8),
                'ended_at'               => now()->subDays(10)->setHour(9),
                'total_duration_minutes'  => 60,
                'price_agreed'            => 1500,
                'status'                  => 'cancelled',
                'worker_confirm'          => 'pending',
                'customer_confirm'        => 'pending',
                'cancel_reason'           => 'ลูกค้ายกเลิกเนื่องจากเปลี่ยนวัน',
            ]
        );

        // ─── Dispute (1 dispute for worker1) ──────────────────────────────────
        Dispute::firstOrCreate(
            ['reporter_id' => $employer1->id, 'respondent_id' => $worker1->id, 'reason' => 'มาถึงช้ากว่านัดหมาย 2 ชั่วโมง'],
            [
                'bookingable_type' => WorkBooking::class,
                'bookingable_id'   => WorkBooking::where('work_id', $demoWorkIds[0] ?? 0)->value('id'),
                'description'      => 'นัดไว้ 08:00 แต่มาถึง 10:00 ทำให้งานล่าช้า',
                'evidence'         => ['https://picsum.photos/seed/dispute-demo/400/300'],
                'status'           => 'resolved',
                'resolution'       => 'ตกลงลดราคาค่าบริการ 20%',
                'admin_id'         => User::first()?->id,
            ]
        );

        $this->command->info('✅ DemoSeeder complete: 4 users, 4 works, 2 recruits + worker1 extras (availabilities, favorites, portfolios, sessions, dispute)');
    }
}

