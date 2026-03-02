<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Recruit;
use App\Models\RecruitReview;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkBooking;
use App\Models\WorkLike;
use App\Models\WorkReview;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * MockSeeder — ข้อมูลจำนวนมากสำหรับเทส
 *
 * Data volumes:
 *  - 50  Users
 *  - 100 Works  (with categories + likes)
 *  - 50  Recruits
 *  - 200 WorkBookings
 *  - 100 RecruitBookings
 *  - 300 Reviews/Posts (works)
 *  - 150 Reviews/Posts (recruits)
 *  - 100 Post replies
 *  - 500 Notifications
 *
 * Run: sail artisan db:seed --class=MockSeeder
 */
class MockSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        // ─── Step 0: Ensure demo accounts exist first ────────────────────────
        // DemoSeeder creates worker1, worker2, employer1, employer2 accounts.
        // MockSeeder only touches @mock.test users so they will NOT conflict.
        $this->call(DemoSeeder::class);

        // ─── Clean up all mock data before re-seeding ────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('works_reviews')->truncate();
        DB::table('recruits_reviews')->truncate();
        DB::table('post_likes')->truncate();
        DB::table('work_likes')->truncate();
        DB::table('user_notifications')->truncate();
        DB::table('work_bookings')->truncate();
        DB::table('recruit_bookings')->truncate();
        DB::table('posts')->truncate();
        Notification::truncate();

        // Delete mock works/recruits and their mock authors
        // Only touch rows authored by @mock.test users to preserve starter/demo data
        $mockUserIds = User::where('email', 'like', '%@mock.test')->pluck('id')->toArray();
        if (!empty($mockUserIds)) {
            DB::table('works_categories')->whereIn('work_id',
                DB::table('works')->whereIn('author_id', $mockUserIds)->pluck('id')
            )->delete();
            DB::table('recruits_categories')->whereIn('recruit_id',
                DB::table('recruits')->whereIn('author_id', $mockUserIds)->pluck('id')
            )->delete();
            DB::table('works')->whereIn('author_id', $mockUserIds)->delete();
            DB::table('recruits')->whereIn('author_id', $mockUserIds)->delete();
        }
        User::where('email', 'like', '%@mock.test')->forceDelete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─── 1. Create Mock Users ─────────────────────────────────────────────
        $this->command->info('Creating 50 mock users...');
        $users = User::factory()
            ->count(50)
            ->sequence(fn ($seq) => [
                'email'         => 'user' . ($seq->index + 1) . '@mock.test',
                'name'          => $this->thaiName($seq->index),
                'first_name'    => $this->thaiFirstName($seq->index),
                'last_name'     => $this->thaiLastName($seq->index),
                'password'      => Hash::make('password'),
                'profile_image' => 'https://i.pravatar.cc/150?img=' . (($seq->index % 70) + 1),
            ])
            ->create();

        $userIds = $users->pluck('id')->toArray();

        // Pick some as workers and some as employers
        $workerIds   = array_slice($userIds, 0, 30);  // first 30 = workers
        $employerIds = array_slice($userIds, 30, 20); // last 20 = employers

        $workTypeIds  = DB::table('work_types')->pluck('id')->toArray() ?: [1];
        $provinceIds  = DB::table('provinces')->pluck('id')->toArray() ?: [1];
        $categoryIds  = Category::pluck('id')->toArray() ?: [1];

        // ─── 2. Create 100 Works ──────────────────────────────────────────────
        $this->command->info('Creating 100 works...');
        $works = Work::factory()
            ->count(100)
            ->sequence(fn ($seq) => [
                'author_id'    => $workerIds[array_rand($workerIds)],
                'province_id'  => $provinceIds[array_rand($provinceIds)],
                'work_type_id' => $workTypeIds[array_rand($workTypeIds)],
            ])
            ->create();

        // Attach random categories to each work
        $works->each(function (Work $work) use ($categoryIds) {
            $work->categories()->sync(
                array_slice($categoryIds, 0, rand(1, min(3, count($categoryIds))))
            );
        });

        // ─── 3. Work Likes ────────────────────────────────────────────────────
        $this->command->info('Creating work likes...');
        $workIds = $works->pluck('id')->toArray();
        foreach ($workIds as $workId) {
            $likers = array_rand(array_flip($employerIds), min(rand(2, 10), count($employerIds)));
            foreach ((array) $likers as $userId) {
                WorkLike::firstOrCreate([
                    'work_id'   => $workId,
                    'author_id' => $userId,
                ]);
            }
        }

        // ─── 4. Create 50 Recruits ────────────────────────────────────────────
        $this->command->info('Creating 50 recruits...');
        $recruits = Recruit::factory()
            ->count(50)
            ->sequence(fn ($seq) => [
                'author_id'    => $employerIds[array_rand($employerIds)],
                'province_id'  => $provinceIds[array_rand($provinceIds)],
                'work_type_id' => $workTypeIds[array_rand($workTypeIds)],
            ])
            ->create();

        $recruitIds = $recruits->pluck('id')->toArray();

        // ─── 5. Work Bookings (200) ────────────────────────────────────────────
        $this->command->info('Creating 200 work bookings...');
        $statuses = ['waiting-to-confirm', 'confirm', 'close', 'cancel'];
        for ($i = 0; $i < 200; $i++) {
            WorkBooking::factory()->create([
                'work_id'        => $workIds[array_rand($workIds)],
                'author_id'      => $employerIds[array_rand($employerIds)],
                'booking_status' => $statuses[array_rand($statuses)],
            ]);
        }

        // ─── 6. Recruit Bookings (100) ────────────────────────────────────────
        $this->command->info('Creating 100 recruit bookings...');
        for ($i = 0; $i < 100; $i++) {
            \App\Models\RecruitBooking::factory()->create([
                'recruit_id'     => $recruitIds[array_rand($recruitIds)],
                'author_id'      => $workerIds[array_rand($workerIds)],
                'booking_status' => $statuses[array_rand($statuses)],
            ]);
        }

        // ─── 7. Work Reviews via posts (300) ──────────────────────────────────
        $this->command->info('Creating 300 work reviews...');
        for ($i = 0; $i < 300; $i++) {
            $workId     = $workIds[array_rand($workIds)];
            $reviewerId = $employerIds[array_rand($employerIds)];

            $post = Post::factory()->create(['author_id' => $reviewerId]);
            WorkReview::create([
                'work_id' => $workId,
                'post_id' => $post->id,
            ]);

            // 30% chance of a reply from the work owner
            if (rand(1, 100) <= 30) {
                $work = Work::find($workId);
                if ($work) {
                    Post::factory()->reply()->create([
                        'author_id' => $work->author_id,
                        'parent_id' => $post->id,
                    ]);
                }
            }
        }

        // ─── 8. Recruit Reviews via posts (150) ───────────────────────────────
        $this->command->info('Creating 150 recruit reviews...');
        for ($i = 0; $i < 150; $i++) {
            $recruitId  = $recruitIds[array_rand($recruitIds)];
            $reviewerId = $workerIds[array_rand($workerIds)];

            $post = Post::factory()->create(['author_id' => $reviewerId]);
            RecruitReview::create([
                'recruit_id' => $recruitId,
                'post_id'    => $post->id,
            ]);
        }

        // ─── 9. Post Likes ────────────────────────────────────────────────────
        $this->command->info('Creating post likes...');
        $postIds = Post::pluck('id')->toArray();
        foreach (array_slice($postIds, 0, 100) as $postId) {
            $likerSample = array_rand(array_flip($userIds), min(rand(1, 5), count($userIds)));
            foreach ((array) $likerSample as $userId) {
                PostLike::firstOrCreate([
                    'post_id'   => $postId,
                    'author_id' => $userId,
                ]);
            }
        }

        // ─── 10. Notifications (500) ──────────────────────────────────────────
        $this->command->info('Creating 500 notifications...');
        Notification::factory()
            ->count(500)
            ->sequence(fn ($seq) => [
                'title'   => 'แจ้งเตือน #' . ($seq->index + 1),
                'content' => json_encode(['message' => 'มีการอัปเดตเกี่ยวกับงานของคุณ หมายเลข ' . ($seq->index + 1)]),
            ])
            ->create();

        Model::reguard();

        $this->command->info('✅ MockSeeder complete!');
        $this->command->table(
            ['Table', 'Count'],
            [
                ['users (mock)',        50],
                ['works',              100],
                ['recruits',            50],
                ['work_bookings',      200],
                ['recruit_bookings',   100],
                ['posts (reviews)',    450],
                ['notifications',      500],
            ]
        );
    }

    private function thaiName(int $i): string
    {
        return $this->thaiFirstName($i) . ' ' . $this->thaiLastName($i);
    }

    private function thaiFirstName(int $i): string
    {
        $names = [
            'สมชาย', 'สมหญิง', 'สมศรี', 'วิชัย', 'วิไล', 'มานะ', 'มาลี',
            'ประสิทธิ์', 'ประภา', 'ชูชาติ', 'ชูชีพ', 'สุชาติ', 'สุมาลี',
            'วรรณา', 'วัชรา', 'รัตนา', 'รัตนาภรณ์', 'พิชัย', 'พิมพ์',
            'ธนา', 'ธนาพร', 'กิตติ', 'กิตติยา', 'นิรันดร์', 'นิภา',
            'อนุชา', 'อนงค์', 'บุญมี', 'บุปผา', 'เกรียงศักดิ์',
        ];
        return $names[$i % count($names)];
    }

    private function thaiLastName(int $i): string
    {
        $names = [
            'ใจดี', 'สุขสรรค์', 'มีสุข', 'ทองคำ', 'รุ่งเรือง',
            'สวัสดี', 'ดีมาก', 'เจริญ', 'สำเร็จ', 'ก้าวหน้า',
            'ศรีสุข', 'วงษ์ทอง', 'สิงห์โต', 'นาคา', 'เพชรรัตน์',
            'หงส์ทอง', 'มณีรัตน์', 'พลอยงาม', 'ทองแดง', 'สีทอง',
        ];
        return $names[$i % count($names)];
    }
}
