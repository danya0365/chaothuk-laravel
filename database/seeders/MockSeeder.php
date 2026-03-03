<?php

namespace Database\Seeders;

use App\Enums\BannerType;
use App\Enums\MessengerConversationType;
use App\Enums\NotificationType;
use App\Enums\UserActivityType;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\IssuePoint;
use App\Models\MessengerChannel;
use App\Models\MessengerConversation;
use App\Models\MessengerParticipant;
use App\Models\Notification;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Recruit;
use App\Models\RecruitReview;
use App\Models\SessionLocationLog;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserNotification;
use App\Models\UserPoint;
use App\Models\Work;
use App\Models\WorkAvailability;
use App\Models\WorkBlockedDate;
use App\Models\WorkBooking;
use App\Models\WorkLike;
use App\Models\WorkReview;
use App\Models\WorkSession;
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
        DB::table('banners')->truncate();
        DB::table('messenger_conversations')->truncate();
        DB::table('messenger_participants')->truncate();
        DB::table('messenger_channels')->truncate();
        DB::table('user_point_logs')->truncate();
        DB::table('user_points')->truncate();
        DB::table('issue_point_status_logs')->truncate();
        DB::table('issue_points')->truncate();
        DB::table('user_activity_logs')->truncate();
        DB::table('favorites')->truncate();
        DB::table('portfolios')->truncate();
        DB::table('session_location_logs')->truncate();
        DB::table('work_sessions')->truncate();
        DB::table('work_blocked_dates')->truncate();
        DB::table('work_availabilities')->truncate();
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

        // Attach random categories to each recruit
        $recruits->each(function (Recruit $recruit) use ($categoryIds) {
            $recruit->categories()->sync(
                array_slice($categoryIds, 0, rand(1, min(3, count($categoryIds))))
            );
        });

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

        // ─── 11. Banners (5) ──────────────────────────────────────────────────
        $this->command->info('Creating 5 banners...');
        $bannerNames = [
            'โปรโมชั่นขนส่งราคาพิเศษ',
            'สมัครสมาชิกวันนี้ รับส่วนลด',
            'บริการขนส่งทั่วประเทศ',
            'แนะนำเพื่อน รับเครดิตฟรี',
            'อัปเดตแอปเวอร์ชั่นใหม่',
        ];
        foreach ($bannerNames as $i => $name) {
            Banner::create([
                'name'         => $name,
                'type'         => BannerType::EXTERNAL_URL->value,
                'image_url'    => 'https://picsum.photos/seed/banner' . ($i + 1) . '/1200/400',
                'external_url' => 'https://chaothuk.com/promo/' . ($i + 1),
                'is_public'    => true,
                'is_pinned'    => $i < 2,
                'expired_at'   => now()->addMonths(3),
            ]);
        }

        // ─── 12. User Notifications (200) ─────────────────────────────────────
        $this->command->info('Creating 200 user notifications...');
        $notifTypes = NotificationType::values();
        for ($i = 0; $i < 200; $i++) {
            $targetUser = $userIds[array_rand($userIds)];
            $notifType  = $notifTypes[array_rand($notifTypes)];
            UserNotification::create([
                'title'              => 'แจ้งเตือน #' . ($i + 1),
                'details'            => ['message' => 'รายละเอียดการแจ้งเตือน ' . ($i + 1)],
                'notification_type'  => $notifType,
                'is_read'            => rand(0, 1),
                'author_id'          => $targetUser,
                'notificationable_type' => Work::class,
                'notificationable_id'   => $workIds[array_rand($workIds)],
            ]);
        }

        // ─── 13. Messenger (10 channels, conversations) ───────────────────────
        $this->command->info('Creating 10 messenger channels with conversations...');
        for ($i = 0; $i < 10; $i++) {
            $user1 = $workerIds[array_rand($workerIds)];
            $user2 = $employerIds[array_rand($employerIds)];

            $channel = MessengerChannel::create([
                'slug'               => 'channel-mock-' . ($i + 1),
                'title'              => 'แชท #' . ($i + 1),
                'is_direct'          => true,
                'is_public'          => false,
                'total_participants'  => 2,
            ]);

            MessengerParticipant::create([
                'user_id'     => $user1,
                'channel_id'  => $channel->id,
                'is_customer' => false,
            ]);
            MessengerParticipant::create([
                'user_id'     => $user2,
                'channel_id'  => $channel->id,
                'is_customer' => true,
            ]);

            // 5 messages per channel
            for ($j = 0; $j < 5; $j++) {
                MessengerConversation::create([
                    'type'          => MessengerConversationType::TEXT->value,
                    'content'       => 'ข้อความทดสอบ ' . ($j + 1) . ' ในแชท #' . ($i + 1),
                    'local_code_id' => 'mock-' . $channel->id . '-' . ($j + 1),
                    'user_id'       => ($j % 2 === 0) ? $user1 : $user2,
                    'channel_id'    => $channel->id,
                ]);
            }
        }

        // ─── 14. Issue Points + User Points (5 issues, 50 user_points) ────────
        $this->command->info('Creating issue points and user points...');
        $issueSlugs = [
            ['slug' => 'daily-login',       'name' => 'เข้าสู่ระบบรายวัน',    'points' => 10],
            ['slug' => 'first-work-post',   'name' => 'โพสต์งานแรก',        'points' => 50],
            ['slug' => 'first-review',      'name' => 'รีวิวแรก',            'points' => 20],
            ['slug' => 'refer-friend',      'name' => 'แนะนำเพื่อน',        'points' => 100],
            ['slug' => 'complete-profile',  'name' => 'กรอกโปรไฟล์ครบ',    'points' => 30],
        ];
        $supervisorId = User::first()->id;
        foreach ($issueSlugs as $issueData) {
            $issue = IssuePoint::create([
                'slug'    => $issueData['slug'],
                'name'    => $issueData['name'],
                'desc'    => 'รายละเอียด: ' . $issueData['name'],
                'points'  => $issueData['points'],
                'type'    => 'one_time',
                'status'  => 'approve',
                'user_id' => $supervisorId,
            ]);

            // Give points to 10 random users per issue
            $luckyUsers = array_rand(array_flip($userIds), min(10, count($userIds)));
            foreach ((array) $luckyUsers as $luckyUserId) {
                UserPoint::create([
                    'point_received'  => $issueData['points'],
                    'point_available' => $issueData['points'],
                    'user_id'         => $luckyUserId,
                    'issue_point_id'  => $issue->id,
                ]);
            }
        }

        // ─── 15. User Activity Logs (100) ─────────────────────────────────────
        $this->command->info('Creating 100 user activity logs...');
        $activityTypes = UserActivityType::values();
        for ($i = 0; $i < 100; $i++) {
            UserActivityLog::create([
                'activity_type'  => $activityTypes[array_rand($activityTypes)],
                'activity_value' => 'mock activity ' . ($i + 1),
                'user_id'        => $userIds[array_rand($userIds)],
            ]);
        }

        // ─── 16. Backfill lat/lng for works/recruits missing coordinates ──────
        $this->command->info('Backfilling lat/lng for works and recruits...');
        $missingWorks = Work::whereNull('latitude')->orWhereNull('longitude')->get();
        foreach ($missingWorks as $w) {
            $w->update([
                'latitude'  => fake()->latitude(13.0, 19.5),
                'longitude' => fake()->longitude(98.0, 104.5),
            ]);
        }
        $missingRecruits = Recruit::whereNull('latitude')->orWhereNull('longitude')->get();
        foreach ($missingRecruits as $r) {
            $r->update([
                'latitude'  => fake()->latitude(13.0, 19.5),
                'longitude' => fake()->longitude(98.0, 104.5),
            ]);
        }
        // ─── 17. Featured Works ─────────────────────────────────────────
        $this->command->info('Creating featured works...');
        $featuredWorkIds = Work::inRandomOrder()->limit(4)->pluck('id', 'author_id');
        $slot = 0;
        foreach ($featuredWorkIds as $authorId => $workId) {
            \App\Models\FeaturedWork::create([
                'work_id'        => $workId,
                'author_id'      => $authorId,
                'start_at'       => now()->subDays(rand(0, 5)),
                'end_at'         => now()->addDays(rand(25, 60)),
                'slot_position'  => $slot++,
                'amount_paid'    => fake()->randomElement([99, 199, 299, 499]),
                'payment_method' => fake()->randomElement(['points', 'transfer']),
                'payment_status' => 'paid',
                'is_approved'    => true,
                'impression_count' => rand(100, 5000),
                'click_count'    => rand(10, 500),
            ]);
        }
        $this->command->info("  → {$slot} featured works created");

        // ─── 18. Province Top Works (calculate from seeded data) ────────
        $this->command->info('Calculating province top works...');
        \Artisan::call('top-works:calculate', ['--period' => now()->format('Y-m')]);
        $topWorksCount = \App\Models\ProvinceTopWork::currentMonth()->count();
        $this->command->info("  → {$topWorksCount} province top works calculated");

        // ─── 19. Favorites (150) ────────────────────────────────────────────
        $this->command->info('Creating 150 favorites...');
        for ($i = 0; $i < 150; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $isWork = rand(0, 1);
            $type   = $isWork ? Work::class : Recruit::class;
            $id     = $isWork ? $workIds[array_rand($workIds)] : $recruitIds[array_rand($recruitIds)];
            Favorite::firstOrCreate([
                'user_id'          => $userId,
                'favoritable_type' => $type,
                'favoritable_id'   => $id,
            ]);
        }

        // ─── 20. Portfolios (60) ────────────────────────────────────────────
        $this->command->info('Creating 60 portfolios...');
        $portfolioTitles = [
            'ขนส่งสินค้าข้ามจังหวัด', 'ซ่อมเครื่องยนต์', 'ขนย้ายบ้าน',
            'ล้างแอร์', 'ทาสีบ้าน', 'ซ่อมประปา', 'งานไฟฟ้า',
            'ขนส่งเฟอร์นิเจอร์', 'รับจ้างทั่วไป', 'งานเชื่อม',
        ];
        foreach ($workerIds as $wIdx => $wId) {
            $numPortfolios = rand(1, 3);
            for ($p = 0; $p < $numPortfolios; $p++) {
                Portfolio::create([
                    'user_id'      => $wId,
                    'title'        => $portfolioTitles[array_rand($portfolioTitles)] . ' #' . ($wIdx + 1) . '-' . ($p + 1),
                    'description'  => 'ผลงานตัวอย่างของฉัน งานเสร็จเรียบร้อย ลูกค้าพอใจ',
                    'images'       => [
                        'https://picsum.photos/seed/port' . $wId . $p . 'a/800/600',
                        'https://picsum.photos/seed/port' . $wId . $p . 'b/800/600',
                    ],
                    'work_type_id' => $workTypeIds[array_rand($workTypeIds)],
                ]);
            }
        }

        // ─── 21. Work Availabilities ─────────────────────────────────────────
        $this->command->info('Creating work availabilities...');
        foreach ($workIds as $wkId) {
            // Mon-Fri 08:00-17:00
            for ($day = 1; $day <= 5; $day++) {
                WorkAvailability::create([
                    'work_id'      => $wkId,
                    'day_of_week'  => $day,
                    'start_time'   => '08:00',
                    'end_time'     => '17:00',
                    'is_available' => true,
                ]);
            }
            // Sat 09:00-12:00 (50% chance)
            if (rand(0, 1)) {
                WorkAvailability::create([
                    'work_id'      => $wkId,
                    'day_of_week'  => 6,
                    'start_time'   => '09:00',
                    'end_time'     => '12:00',
                    'is_available' => true,
                ]);
            }
            // Sun = off (30% chance of having blocked dates)
            if (rand(1, 100) <= 30) {
                WorkBlockedDate::create([
                    'work_id'      => $wkId,
                    'blocked_date' => now()->addDays(rand(1, 30))->toDateString(),
                    'reason'       => fake()->randomElement(['ลาพักร้อน', 'วันหยุดนักขัตฤกษ์', 'ซ่อมรถ', 'ธุระส่วนตัว']),
                ]);
            }
        }

        // ─── 22. Work Sessions (80) + Location Logs ──────────────────────────
        $this->command->info('Creating 80 work sessions with location logs...');
        $sessionStatuses = ['active', 'paused', 'completed', 'completed', 'completed', 'cancelled'];
        for ($i = 0; $i < 80; $i++) {
            $isWork   = rand(0, 1);
            $workerId = $workerIds[array_rand($workerIds)];
            $custId   = $employerIds[array_rand($employerIds)];
            $status   = $sessionStatuses[array_rand($sessionStatuses)];
            $startedAt = now()->subDays(rand(1, 60))->subHours(rand(1, 8));
            $duration  = rand(30, 480);
            $endedAt   = in_array($status, ['completed', 'cancelled']) ? $startedAt->copy()->addMinutes($duration) : null;

            // 60% from booking, 40% walk-in
            $hasBooking = rand(1, 100) <= 60;

            $session = WorkSession::create([
                'sessionable_type'  => $isWork ? Work::class : Recruit::class,
                'sessionable_id'    => $isWork ? $workIds[array_rand($workIds)] : $recruitIds[array_rand($recruitIds)],
                'worker_id'         => $workerId,
                'customer_id'       => $custId,
                'bookingable_type'  => $hasBooking ? ($isWork ? WorkBooking::class : \App\Models\RecruitBooking::class) : null,
                'bookingable_id'    => $hasBooking ? rand(1, $isWork ? 200 : 100) : null,
                'started_at'        => $startedAt,
                'ended_at'          => $endedAt,
                'total_duration_minutes' => $endedAt ? $duration : 0,
                'price_agreed'      => fake()->randomElement([500, 800, 1000, 1500, 2000, 3000, 5000]),
                'status'            => $status,
                'worker_confirm'    => $status === 'completed' ? 'confirmed' : 'pending',
                'customer_confirm'  => $status === 'completed' ? 'confirmed' : 'pending',
            ]);

            // Add 5-15 location logs per session
            $baseLat = fake()->latitude(13.5, 14.5);
            $baseLng = fake()->longitude(100.0, 101.0);
            $logCount = rand(5, 15);
            for ($l = 0; $l < $logCount; $l++) {
                SessionLocationLog::create([
                    'session_id'  => $session->id,
                    'user_id'     => $workerId,
                    'latitude'    => $baseLat + ($l * 0.001 * (rand(0, 1) ? 1 : -1)),
                    'longitude'   => $baseLng + ($l * 0.001 * (rand(0, 1) ? 1 : -1)),
                    'accuracy'    => rand(5, 50),
                    'speed'       => rand(0, 80) / 10,
                    'heading'     => rand(0, 360),
                    'recorded_at' => $startedAt->copy()->addMinutes($l * 3),
                ]);
            }
        }

        Model::reguard();

        $this->command->info('✅ MockSeeder complete!');
        $this->command->table(
            ['Table', 'Count'],
            [
                ['users (mock)',          50],
                ['works',                100],
                ['recruits',              50],
                ['work_bookings',        200],
                ['recruit_bookings',     100],
                ['posts (reviews)',      450],
                ['notifications',        500],
                ['banners',                5],
                ['user_notifications',   200],
                ['messenger_channels',    10],
                ['issue_points',           5],
                ['user_points',           50],
                ['user_activity_logs',   100],
                ['favorites',            150],
                ['portfolios',           '~60'],
                ['work_availabilities',  '~600'],
                ['work_sessions',         80],
                ['session_location_logs','~800'],
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
