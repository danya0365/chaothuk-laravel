<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCoupon;
use App\Http\Resources\UserCouponResource;
use Illuminate\Http\Request;

/**
 * Class UserCouponController
 * @package App\Http\Controllers
 */
class UserCouponController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userCoupon = UserCoupon::with('bannerProduct')->find($id);

        return response()->json([
            'status' => $userCoupon ? true : false,
            'data' => new UserCouponResource($userCoupon),
        ], 200);
    }

    /**
     * Display the specified resource by code.
     */
    public function searchByCode($code)
    {
        $userCoupon = UserCoupon::with('bannerProduct')->where('code', $code)->first();

        return response()->json([
            'status' => $userCoupon ? true : false,
            'data' => new UserCouponResource($userCoupon),
        ], 200);
    }


    /**
     * Use a Coupon.
     */
    public function use(Request $request)
    {
        $merchant = auth('sanctum')->user();
        $userCoupon = UserCoupon::with('bannerProduct')->where('code', $request->get('code'))->first();
        if (!$userCoupon) {
            return response()->json([
                'status' => false,
                'message' => '$userCoupon not found',
            ], 403);
        }

        if (!$merchant) {
            return response()->json([
                'status' => false,
                'message' => '$merchant not found',
            ], 411);
        }

        if ($merchant->id != $userCoupon->bannerProduct->merchant_id) {
            return response()->json([
                'status' => false,
                'message' => 'wrong $merchant',
            ], 412);
        }

        if ($userCoupon->coupon_available <= 0) {
            return response()->json([
                'status' => false,
                'message' => '$userCoupon not available',
            ], 413);
        }

        $userCoupon->coupon_available -= 1;
        $userCoupon->save();

        return response()->json([
            'status' => $userCoupon ? true : false,
            'data' => new UserCouponResource($userCoupon),
        ], 200);
    }


    /**
     * Backend use a Coupon.
     */
    public function backendUse(Request $request)
    {
        $backend = $request->user();
        $userCoupon = UserCoupon::with('bannerProduct')->where('code', $request->get('code'))->first();
        if (!$userCoupon) {
            return response()->json([
                'status' => false,
                'message' => '$userCoupon not found',
            ], 403);
        }

        if (!$backend) {
            return response()->json([
                'status' => false,
                'message' => '$backend not found',
            ], 411);
        }

        if ($userCoupon->coupon_available <= 0) {
            return response()->json([
                'status' => false,
                'message' => '$userCoupon not available',
            ], 413);
        }

        $userCoupon->coupon_available -= 1;
        $userCoupon->save();

        return response()->json([
            'status' => $userCoupon ? true : false,
            'data' => new UserCouponResource($userCoupon),
        ], 200);
    }
}
