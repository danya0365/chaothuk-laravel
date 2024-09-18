<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCouponCollection;
use App\Http\Resources\UserCouponLogCollection;
use App\Http\Resources\UserMissionCollection;
use App\Models\UserCoupon;
use App\Models\UserCouponLog;
use App\Models\UserMission;
use Illuminate\Http\Request;

/**
 * Class MerchantLogController
 * @package App\Http\Controllers
 */
class MerchantLogController extends Controller
{
    public function getCoupons(Request $request)
    {
        $user = $request->user();
        $coupons = UserCoupon::query()->with(['customer'])
            ->whereHas('bannerProduct', function ($query) use ($user) {
                $query->where('merchant_id', $user->id);
            })
            ->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserCouponCollection($coupons),
        ], 200);
    }

    public function getMissions(Request $request)
    {
        $user = $request->user();
        $missions = UserMission::query()->with(['customer'])
            ->whereHas('bannerPromotion', function ($query) use ($user) {
                $query->where('merchant_id', $user->id);
            })
            ->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserMissionCollection($missions),
        ], 200);
    }


    public function getCouponLogs(Request $request)
    {
        $user = $request->user();
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = UserCouponLog::query()
            ->with(['userCoupon' => function ($query) {
                $query->with('bannerProduct')->with('customer');
            }])
            ->whereHas('userCoupon', function ($query) use ($user) {
                $query->with(['customer'])->whereHas('bannerProduct', function ($query) use ($user) {
                    $query->where('merchant_id', $user->id);
                });
            });

        if ($startDate && $endDate)
            $query->whereBetween('created_at', [$startDate, $endDate]);

        $pointLogs = $query->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserCouponLogCollection($pointLogs),
        ], 200);
    }
}
