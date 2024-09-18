<?php

namespace App\Http\Controllers\Api;

use App\Enums\BannerType;
use App\Enums\MissionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerCollection;
use App\Http\Resources\BannerResource;
use App\Http\Resources\UserCouponCollection;
use App\Http\Resources\UserCouponResource;
use App\Http\Resources\UserMissionCollection;
use App\Http\Resources\UserMissionResource;
use App\Models\Banner;
use App\Models\BannerProduct;
use App\Models\BannerPromotion;
use App\Models\PointTransactionLog;
use App\Models\UserCoupon;
use App\Models\UserCouponLog;
use App\Models\UserMission;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class BannerController
 * @package App\Http\Controllers
 */
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $banners = Banner::query()->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }

    /**
     * Display a pinned listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pinnedBanners()
    {
        $banners = Banner::where('is_pinned', 1)->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }

    /**
     * Display a bannerProducts listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bannerProducts(Request $request)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }
        $customerUserTypes = $customer->userTypes ?? [];
        $customerUserTypesId = [];
        foreach ($customerUserTypes as $customerUserType) {
            $customerUserTypesId[] = $customerUserType->id;
        }

        $banners = Banner::with('bannerProduct')
            ->where('type', BannerType::PRODUCT->value)
            ->whereHas('bannerProduct', function ($query) use ($customerUserTypesId) {
                $query->whereHas('userTypes', function ($query) use ($customerUserTypesId) {
                    $query->whereIn('user_types.id', $customerUserTypesId);
                });
            })
            ->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }

    /**
     * Display a bannerPromotions listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bannerPromotions(Request $request)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }
        $customerUserTypes = $customer->userTypes ?? [];
        $customerUserTypesId = [];
        foreach ($customerUserTypes as $customerUserType) {
            $customerUserTypesId[] = $customerUserType->id;
        }

        $banners = Banner::with('bannerPromotion')
            ->where('type', BannerType::PROMOTION->value)
            ->whereHas('bannerPromotion', function ($query) use ($customerUserTypesId) {
                $query->whereHas('userTypes', function ($query) use ($customerUserTypesId) {
                    $query->whereIn('user_types.id', $customerUserTypesId);
                });
            })
            ->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new BannerCollection($banners),
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banner = Banner::with(['bannerProduct' => function ($q) {
            return $q->with('merchant');
        }])->with(['bannerPromotion' => function ($q) {
            return $q->with('merchant');
        }])->where('id', $id)->first();

        if ($banner) {
            $banner->view_count += 1;
            $banner->save();
        }

        return response()->json([
            'status' => $banner ? true : false,
            'data' => new BannerResource($banner),
        ], 200);
    }

    /**
     * Display lastUpdate.
     *
     * @return \Illuminate\Http\Response
     */
    public function lastUpdate()
    {
        $banner = Banner::orderBy('updated_at', 'desc')->take(1)->first();

        return response()->json([
            'status' => $banner ? true : false,
            'data' => $banner ? new BannerResource($banner) : null,
        ], 200);
    }

    /**
     * listUserMissions
     *
     * @return \Illuminate\Http\Response
     */
    public function listUserMissions(Request $request, $id)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }

        $userMissions = UserMission::where('user_id', $customer->id)
            ->where('banner_promotion_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserMissionCollection($userMissions),
        ], 200);
    }

    /**
     * storeUserMission
     *
     * @return \Illuminate\Http\Response
     */
    public function storeUserMission(Request $request, $id)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }
        $bannerPromotion = BannerPromotion::find($id);
        if (!$bannerPromotion) {
            return response()->json([
                'status' => false,
                'message' => '$bannerPromotion not found',
            ], 403);
        }

        $customerUserTypes = $customer->userTypes ?? [];
        $bannerUserTypes = $bannerPromotion->userTypes ?? [];
        $foundUserType = false;
        foreach ($bannerUserTypes as $bannerUserType) {
            foreach ($customerUserTypes as $customerUserType) {
                if ($customerUserType->id == $bannerUserType->id) {
                    $foundUserType = true;
                }
            }
        }
        if (!$foundUserType) {
            return response()->json([
                'status' => false,
                'message' => '$foundUserType not found',
                'data' => ['$customerUserTypes' => $customerUserTypes, '$bannerUserTypes' => $bannerUserTypes]
            ], 411);
        }

        if ($bannerPromotion->isExpired()) {
            return response()->json([
                'status' => false,
                'message' => '$bannerPromotion expired',
            ], 412);
        }

        if (!$bannerPromotion->isMissionAvailable()) {
            return response()->json([
                'status' => false,
                'message' => 'available_missions == 0',
            ], 413);
        }

        $inProgressUserMission = UserMission::where('user_id', $customer->id)
            ->where('banner_promotion_id', $id)
            ->where('status',  MissionStatus::IN_PROGRESS->value)
            ->orderBy('id', 'desc')
            ->first();

        if ($inProgressUserMission) {
            return response()->json([
                'status' => $inProgressUserMission ? true : false,
                'data' => $inProgressUserMission ? new UserMissionResource($inProgressUserMission) : null,
            ], 200);
        }

        $completedUserMissions = UserMission::where('user_id', $customer->id)
            ->where('banner_promotion_id', $id)
            ->where('status',  MissionStatus::COMPLETE->value)
            ->orderBy('id', 'desc')
            ->get();

        if (count($completedUserMissions) >= $bannerPromotion->max_mission_per_user && $bannerPromotion->max_mission_per_user != -1) {
            return response()->json([
                'status' => false,
                'message' => 'max_mission_per_user ' . $bannerPromotion->max_mission_per_user,
            ], 414);
        }

        $userMission = UserMission::create([
            'user_id' => $customer->id,
            'points' => $bannerPromotion->acquire_points,
            'status' => MissionStatus::IN_PROGRESS->value,
            'banner_promotion_id' => $id
        ]);

        if ($userMission && $bannerPromotion->available_missions != -1) {
            $bannerPromotion->available_missions -= 1;
            $bannerPromotion->save();
        }

        return response()->json([
            'status' => $userMission ? true : false,
            'data' => $userMission ? new UserMissionResource($userMission) : null,
        ], 201);
    }


    /**
     * listUserCoupon
     *
     * @return \Illuminate\Http\Response
     */
    public function listUserCoupons(Request $request, $id)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }

        $userMissions = UserCoupon::where('user_id', $customer->id)
            ->where('banner_product_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserCouponCollection($userMissions),
        ], 200);
    }


    /**
     * storeUserCoupon
     *
     * @return \Illuminate\Http\Response
     */
    public function storeUserCoupon(Request $request, $id)
    {
        $customer = $request->user();
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => '$customer not found',
            ], 403);
        }
        $bannerProduct = BannerProduct::find($id);
        if (!$bannerProduct) {
            return response()->json([
                'status' => false,
                'message' => '$bannerProduct not found',
            ], 403);
        }

        $customerUserTypes = $customer->userTypes ?? [];
        $bannerUserTypes = $bannerProduct->userTypes ?? [];
        $foundUserType = false;
        foreach ($bannerUserTypes as $bannerUserType) {
            foreach ($customerUserTypes as $customerUserType) {
                if ($customerUserType->id == $bannerUserType->id) {
                    $foundUserType = true;
                }
            }
        }
        if (!$foundUserType) {
            return response()->json([
                'status' => false,
                'message' => '$foundUserType not found',
                'data' => ['$customerUserTypes' => $customerUserTypes, '$bannerUserTypes' => $bannerUserTypes]
            ], 411);
        }

        if ($bannerProduct->isExpired()) {
            return response()->json([
                'status' => false,
                'message' => '$bannerProduct expired',
            ], 412);
        }

        if (!$bannerProduct->isRedeemAvailable()) {
            return response()->json([
                'status' => false,
                'message' => 'available_redeems == 0',
            ], 413);
        }

        $availableUserCoupon = UserCoupon::where('user_id', $customer->id)
            ->where('banner_product_id', $id)
            ->where('coupon_available',  '>', 0)
            ->orderBy('id', 'desc')
            ->first();

        if ($availableUserCoupon) {
            return response()->json([
                'status' => $availableUserCoupon ? true : false,
                'data' => $availableUserCoupon ? new UserCouponResource($availableUserCoupon) : null,
            ], 200);
        }

        $notAvailableUserCoupons = UserCoupon::where('user_id', $customer->id)
            ->where('banner_product_id', $id)
            ->where('coupon_available',  0)
            ->orderBy('id', 'desc')
            ->get();

        if (count($notAvailableUserCoupons) >= $bannerProduct->max_redeem_per_user && $bannerProduct->max_redeem_per_user != -1) {
            return response()->json([
                'status' => false,
                'message' => 'max_redeem_per_user ' . $bannerProduct->max_redeem_per_user,
            ], 414);
        }

        if ($customer->availablePoints() < $bannerProduct->redeem_points) {
            return response()->json([
                'status' => false,
                'message' => 'not enough customer point',
            ], 415);
        }

        $redeemPoints = $bannerProduct->redeem_points;
        $remainPayment = $redeemPoints;
        $userPointLogs = [];
        while ($remainPayment > 0) {
            $userPoint = UserPoint::query()->whereBelongsTo($customer, 'user')->where(function ($q) {
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
        $couponCode = \Illuminate\Support\Str::random(8);
        $userCoupon = UserCoupon::create([
            'user_id' => $customer->id,
            'code' => strtoupper($couponCode),
            'coupon_received' => 1,
            'coupon_available' => 1,
            'expired_at' => $bannerProduct->calculateCouponExpireAt(),
            'banner_product_id' => $id
        ]);

        $pointTransactionLog = new PointTransactionLog();
        //$pointTransactionLog->user_id = $customer->id;
        $pointTransactionLog->points = -$redeemPoints;
        $pointTransactionLog->user_point_logs = $userPointLogs;
        $pointTransactionLog->transactionable()->associate($userCoupon);
        //$pointTransactionLog->save();
        $customer->transactions()->save($pointTransactionLog);

        if ($userCoupon) {
            $userCoupon->code = sprintf('%s%s%06d', UserCoupon::$codePrefix, date('ymd'), $userCoupon->id);
            $userCoupon->save();

            UserCouponLog::create([
                'coupons' => 1,
                'user_coupon_id' => $userCoupon->id,
                'action_user_id' => 1,
            ]);
            foreach ($userPointLogs as $userPointLog) {
                $userPointLog->user_coupon_id = $userCoupon->id;
                $userPointLog->point_transaction_id = $pointTransactionLog->id;
                $userPointLog->save();
            }
        }

        if ($userCoupon && $bannerProduct->available_redeems != -1) {
            $bannerProduct->available_redeems -= 1;
            $bannerProduct->save();
        }

        return response()->json([
            'status' => $userCoupon ? true : false,
            'data' => $userCoupon ? new UserCouponResource($userCoupon) : null,
        ], 201);
    }
}
