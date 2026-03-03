<?php

namespace Database\Seeders;

use App\Enums\BannerType;
use App\Enums\CouponExpiresType;
use App\Enums\Gender;
use App\Enums\IssueStatus;
use App\Enums\IssueType;
use App\Enums\MissionStatus;
use App\Enums\NotificationType;
use App\Enums\PersonType;
use App\Enums\PromotionType;
use App\Enums\Role;
use App\Models\Banner;
use App\Models\BannerProduct;
use App\Models\BannerPromotion;
use App\Models\IssuePoint;
use App\Models\IssuePointStatusLog;
use App\Models\Notification;
use App\Models\PointTransactionLog;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserCouponLog;
use App\Models\UserCustomer;
use App\Models\UserMerchant;
use App\Models\UserMission;
use App\Models\UserMissionStatusLog;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::reguard();
        Notification::truncate();
        Notification::factory()
            ->count(500)
            ->sequence(fn ($sequence) => [
                'title' => 'แจ้งเตือน ' . $sequence->index + 1,
                'content' => 'ข้อความการแจ้งเตือน ' . $sequence->index + 1,
                'notification_type' => NotificationType::GENERAL->value
            ])
            ->create();
    }

}
