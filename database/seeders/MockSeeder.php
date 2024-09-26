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

        $userMerchant = $this->createMerchant();
        $userCustomer = $this->createCustomer();

        $bannerProducts = $this->createBannerProduct($userMerchant);
        $bannerPromotions = $this->createBannerPromotion($userMerchant);
        $bannerExternalUrl = $this->createBannerExternalUrl();

        $this->createUserMissions($userCustomer, $bannerPromotions);
        $this->createUserCoupons($userCustomer, $bannerProducts);
    }

    /**
     * @param User $userCustomer
     * @param BannerProduct[] $bannerProducts
     */
    public function createUserCoupons(User $userCustomer, array $bannerProducts)
    {
        $bannerProduct = $bannerProducts[0];

        $redeemPoints = $bannerProduct->redeem_points;
        $remainPayment = $redeemPoints;
        $userPointLogs = [];
        while ($remainPayment > 0) {
            $userPoint = UserPoint::query()->whereBelongsTo($userCustomer, 'user')->where(function ($q) {
                return $q->whereNull('expired_at')->orWhere('expired_at', '>=', Carbon::now());
            })->where('point_available', '>', 0)->orderBy('id', 'asc')->take(1)->first();
            if (!$userPoint) break;
            $cut = 0;
            if ($userPoint->point_available >= $remainPayment) {
                $cut = $remainPayment;
            } else {
                $cut = $userPoint->point_available;
            }
            $userPoint->point_available -= $cut;
            $userPoint->save();
            $remainPayment -= $cut;
            $userPointLogs[] = UserPointLog::create([
                'points' => -$cut,
                'user_point_id' => $userPoint->id,
                'action_user_id' => 1,
            ]);
        }
        if ($remainPayment === 0) {
            $coupons = 1;

            $code = \Illuminate\Support\Str::random(8);
            $couponCode = strtoupper("$code");
            $userCoupon = UserCoupon::create([
                'user_id' => $userCustomer->id,
                'code' => $couponCode,
                'coupon_received' => $coupons,
                'coupon_available' => $coupons,
                'expired_at' => Carbon::createFromFormat('Y-m-d', '2025-06-30'),
                'banner_product_id' => $bannerProduct->id
            ]);

            $pointTransactionLog = new PointTransactionLog();
            //$pointTransactionLog->user_id = $customer->id;
            $pointTransactionLog->points = -$redeemPoints;
            $pointTransactionLog->user_point_logs = $userPointLogs;
            $pointTransactionLog->transactionable()->associate($userCoupon);
            //$pointTransactionLog->save();
            $userCustomer->transactions()->save($pointTransactionLog);

            if ($userCoupon) {
                $userCoupon->code = sprintf('%s%s%06d', UserCoupon::$codePrefix, date('ymd'), $userCoupon->id);
                $userCoupon->save();
                UserCouponLog::create([
                    'coupons' => $coupons,
                    'user_coupon_id' => $userCoupon->id,
                    'action_user_id' => 1,
                ]);
                foreach ($userPointLogs as $userPointLog) {
                    $userPointLog->user_coupon_id = $userCoupon->id;
                    $userPointLog->point_transaction_id = $pointTransactionLog->id;
                    $userPointLog->save();
                }
            }
        }
    }

    /**
     * @param User $userCustomer
     * @param BannerPromotion[] $bannerPromotions
     */
    public function createUserMissions(User $userCustomer, array $bannerPromotions)
    {
        $bannerPromotion1 = $bannerPromotions[0];
        $bannerPromotion2 = $bannerPromotions[1];
        //
        $userMission = UserMission::create([
            'user_id' => $userCustomer->id,
            'points' => $bannerPromotion1->acquire_points,
            'status' => MissionStatus::COMPLETE->value,
            'note' => 'ยินดีด้วย',
            'banner_promotion_id' => $bannerPromotion1->id
        ]);

        if ($userMission->isComplete()) {
            $slug = 'mission';
            $name = 'mission';
            $desc = 'mission';
            $points = $userMission->points;
            $type = IssueType::ONE_TIME->value;
            $status = IssueStatus::APPROVE->value;
            $userId = $userMission->user_id;
            $issuePoint = IssuePoint::create([
                'slug' => $slug,
                'name' => $name,
                'desc' => $desc,
                'points' => $points,
                'type' => $type,
                'status' => $status,
                'user_id' => $userId,
                'user_mission_id' => $userMission->id
            ]);
            if ($issuePoint) {
                $issuePointStatusLog = IssuePointStatusLog::create([
                    'issue_point_status' => $issuePoint->status,
                    'issue_point_id' => $issuePoint->id,
                    'action_user_id' => 1,
                ]);
                $userPoint = UserPoint::create([
                    'point_received' => $points,
                    'point_available' => $points,
                    'user_id' => $userId,
                    'issue_point_id' => $issuePoint->id,
                    'expired_at' => null,
                ]);
                if ($userPoint) {
                    $userPointLog = UserPointLog::create([
                        'points' => $points,
                        'user_point_id' => $userPoint->id,
                        'action_user_id' => 1,
                    ]);
                    $pointTransactionLog = new PointTransactionLog();
                    $pointTransactionLog->user_id = $userId;
                    $pointTransactionLog->points = $points;
                    $pointTransactionLog->user_point_logs = [$userPointLog];
                    $pointTransactionLog->transactionable()->associate($userMission);
                    $pointTransactionLog->save();
                    //$userCustomer->transactions()->save($pointTransactionLog);
                }
            }

            UserMissionStatusLog::create([
                'user_mission_status' => $userMission->status,
                'user_mission_id' => $userMission->id,
                'action_user_id' => 1,
            ]);
        }

        UserMission::create([
            'user_id' => $userCustomer->id,
            'points' => $bannerPromotion2->acquire_points,
            'status' => MissionStatus::IN_PROGRESS->value,
            'banner_promotion_id' => $bannerPromotion2->id
        ]);
        UserMission::create([
            'user_id' => $userCustomer->id,
            'points' => $bannerPromotion1->acquire_points,
            'status' => MissionStatus::IN_PROGRESS->value,
            'banner_promotion_id' => $bannerPromotion1->id
        ]);
    }

    public function createMerchant()
    {
        $userMerchant = User::create([
            'name' => 'user.merchant1',
            'email' => 'user.merchant1@gmail.com',
            'password' => Hash::make('123456'),
            'role_id' => Role::MERCHANT->value,
        ]);
        $post = [
            'user_id' => $userMerchant->id,
            'name' => 'Merchant 1',
            'image_url' => 'image url',
            'desc' => 'รายละเอียดร้าน merchant1',
            'address' => 'ที่อยู่ร้าน merchant1',
            'province' => 'นราธิวาส',
        ];
        UserMerchant::create($post);
        return $userMerchant;
    }

    public function createBannerProduct(User $userMerchant)
    {
        $code = \Illuminate\Support\Str::random(8);
        $bannerCode = strtoupper("PROD-$code");
        $bannerProduct1 = BannerProduct::create([
            'code' => $bannerCode,
            'name' => 'สินค้า 1',
            'detail' => 'รายละเอียดสินค้า 1',
            'condition_text' => 'เงื่อนไขสินค้า 1',
            'tags' => ['sport', 'fashion', 'it'],
            'image_url' => 'image_url',
            'expired_at' => Carbon::createFromFormat('Y-m-d', '2025-06-30'),
            'redeem_points' => 5000,
            'available_redeems' => 99999,
            'max_redeem_per_user' => -1,
            'coupon_expires_type' => CouponExpiresType::TYPE_1->value,
            'merchant_id' => $userMerchant->id,
        ]);
        $bannerProduct1->syncUserType([1, 2]);
        $bannerProduct1->code = sprintf('%s%s%04d', BannerProduct::$codePrefix, date('ymd'), $bannerProduct1->id);
        $bannerProduct1->save();
        Banner::create([
            'name' => 'แบนเนอร์สินค้า 1',
            'view_count' => 1,
            'type' => BannerType::PRODUCT->value,
            'image_url' => 'image_url',
            'is_public' => 1,
            'banner_product_id' => $bannerProduct1->id,
            'is_pinned' => 1
        ]);

        $code = \Illuminate\Support\Str::random(8);
        $bannerCode = strtoupper("PROD-$code");
        $bannerProduct2 = BannerProduct::create([
            'code' => $bannerCode,
            'name' => 'สินค้า 2',
            'detail' => 'รายละเอียดสินค้า 2',
            'condition_text' => 'เงื่อนไขสินค้า 2',
            'tags' => ['sport', 'fashion', 'it'],
            'image_url' => 'image_url',
            'expired_at' => Carbon::createFromFormat('Y-m-d', '2025-06-30'),
            'redeem_points' => 8000,
            'available_redeems' => -1,
            'max_redeem_per_user' => -1,
            'coupon_expires_type' => CouponExpiresType::TYPE_2->value,
            'merchant_id' => $userMerchant->id,
        ]);
        $bannerProduct2->syncUserType([1, 2]);
        $bannerProduct2->code = sprintf('%s%s%04d', BannerProduct::$codePrefix, date('ymd'), $bannerProduct2->id);
        $bannerProduct2->save();
        Banner::create([
            'name' => 'แบนเนอร์สินค้า 2',
            'view_count' => 1,
            'type' => BannerType::PRODUCT->value,
            'image_url' => 'image_url',
            'is_public' => 1,
            'banner_product_id' => $bannerProduct2->id
        ]);

        $code = \Illuminate\Support\Str::random(8);
        $bannerCode = strtoupper("PROD-$code");
        $bannerProduct3 = BannerProduct::create([
            'code' => $bannerCode,
            'name' => 'สินค้า 3',
            'detail' => 'รายละเอียดสินค้า 3',
            'condition_text' => 'เงื่อนไขสินค้า 3',
            'tags' => ['sport', 'fashion', 'it'],
            'image_url' => 'image_url',
            'expired_at' => Carbon::createFromFormat('Y-m-d', '2025-06-30'),
            'redeem_points' => 0,
            'available_redeems' => 100,
            'max_redeem_per_user' => 1,
            'coupon_expires_type' => CouponExpiresType::TYPE_1->value,
            'merchant_id' => $userMerchant->id,
        ]);
        $bannerProduct3->syncUserType([1, 2]);
        $bannerProduct3->code = sprintf('%s%s%04d', BannerProduct::$codePrefix, date('ymd'), $bannerProduct3->id);
        $bannerProduct3->save();
        return [$bannerProduct1, $bannerProduct2, $bannerProduct3];
    }

    public function createBannerPromotion(User $userMerchant)
    {
        $code = \Illuminate\Support\Str::random(8);
        $bannerCode = strtoupper("PROM-$code");
        $bannerPromotion1 = BannerPromotion::create([
            'type' => PromotionType::MISSION->value,
            'code' => $bannerCode,
            'name' => 'โปรโมชั่น 1',
            'detail' => 'รายละเอียดโปรโมชั่น 1',
            'condition_text' => 'เงื่อนไขโปรโมชั่น 1',
            'tags' => ['sport', 'fashion', 'it'],
            'image_url' => 'image_url',
            'expired_at' => Carbon::createFromFormat('Y-m-d', '2025-06-30'),
            'acquire_points' => 5000,
            'available_missions' => 99999,
            'max_mission_per_user' => -1,
            'merchant_id' => $userMerchant->id,
        ]);
        $bannerPromotion1->syncUserType([1, 2]);
        $bannerPromotion1->code = sprintf('%s%s%04d', BannerPromotion::$codePrefix, date('ymd'), $bannerPromotion1->id);
        $bannerPromotion1->save();
        Banner::create([
            'name' => 'แบนเนอร์โปรโมชั่น 1',
            'view_count' => 1,
            'type' => BannerType::PROMOTION->value,
            'image_url' => 'image_url',
            'is_public' => 1,
            'banner_promotion_id' => $bannerPromotion1->id,
            'is_pinned' => 1
        ]);

        $code = \Illuminate\Support\Str::random(8);
        $bannerCode = strtoupper("PROM-$code");
        $bannerPromotion2 = BannerPromotion::create([
            'type' => PromotionType::MISSION->value,
            'code' => $bannerCode,
            'name' => 'โปรโมชั่น 2',
            'detail' => 'รายละเอียดโปรโมชั่น 2',
            'condition_text' => 'เงื่อนไขโปรโมชั่น 2',
            'tags' => ['sport', 'fashion', 'it'],
            'image_url' => 'image_url',
            'expired_at' => Carbon::createFromFormat('Y-m-d', '2025-06-30'),
            'acquire_points' => 7000,
            'available_missions' => -1,
            'max_mission_per_user' => -1,
            'merchant_id' => $userMerchant->id,
        ]);
        $bannerPromotion2->syncUserType([1, 2]);
        $bannerPromotion2->code = sprintf('%s%s%04d', BannerPromotion::$codePrefix, date('ymd'), $bannerPromotion2->id);
        $bannerPromotion2->save();
        Banner::create([
            'name' => 'แบนเนอร์โปรโมชั่น 2',
            'view_count' => 1,
            'type' => BannerType::PROMOTION->value,
            'image_url' => 'image_url',
            'is_public' => 1,
            'banner_promotion_id' => $bannerPromotion2->id
        ]);

        $code = \Illuminate\Support\Str::random(8);
        $bannerCode = strtoupper("PROM-$code");
        $bannerPromotion3 = BannerPromotion::create([
            'type' => PromotionType::NORMAL->value,
            'code' => $bannerCode,
            'name' => 'โปรโมชั่น 3',
            'detail' => 'รายละเอียดโปรโมชั่น 3',
            'tags' => ['sport', 'fashion', 'it'],
            'image_url' => 'image_url',
            'merchant_id' => $userMerchant->id,
        ]);
        $bannerPromotion3->code = sprintf('%s%s%04d', BannerPromotion::$codePrefix, date('ymd'), $bannerPromotion3->id);
        $bannerPromotion3->save();
        Banner::create([
            'name' => 'แบนเนอร์โปรโมชั่น 3',
            'view_count' => 1,
            'type' => BannerType::PROMOTION->value,
            'image_url' => 'image_url',
            'is_public' => 1,
            'banner_promotion_id' => $bannerPromotion3->id
        ]);
        return [$bannerPromotion1, $bannerPromotion2, $bannerPromotion3];
    }

    public function createBannerExternalUrl()
    {
        $banner1 = Banner::create([
            'name' => 'แบนเนอร์ url 1',
            'view_count' => 1,
            'type' => BannerType::EXTERNAL_URL->value,
            'image_url' => 'image_url',
            'external_url' => 'https://app.thaiacegroup.com/',
            'is_public' => 1,
            'is_pinned' => 1
        ]);
        $banner2 = Banner::create([
            'name' => 'แบนเนอร์ url 2',
            'view_count' => 1,
            'type' => BannerType::EXTERNAL_URL->value,
            'image_url' => 'image_url',
            'external_url' => 'https://app.thaiacegroup.com/',
            'is_public' => 1,
        ]);


        return [$banner1, $banner2];
    }

    public function createCustomer()
    {
        $userCustomer = User::create([
            'name' => 'user.customer1',
            'email' => 'user.customer1@gmail.com',
            'password' => Hash::make('123456'),
            'role_id' => Role::CUSTOMER->value,
        ]);

        $post = [
            'user_id' => $userCustomer->id,
            'person_type' => PersonType::NATURAL->value,
            'first_name' => 'customer1',
            'last_name' => 'last',
            'gender' => Gender::MALE->value,
            'identification_no' => '1234566678909',
            'birth_date' => '1986-06-30',
            'mobile_phone' => '0912345678',
        ];
        $post['person_info'] = $post;
        UserCustomer::create($post);
        $userCustomer->syncUserTypes([
            ['user_type_id' => 1, 'amount' => '5000', 'text_condition' => 'good'],
            ['user_type_id' => 2, 'amount' => '8000', 'text_condition' => 'excellent']
        ]);
        return $userCustomer;
    }
}
