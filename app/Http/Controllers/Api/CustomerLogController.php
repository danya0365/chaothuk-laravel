<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IssuePointCollection;
use App\Http\Resources\PointTransactionLogCollection;
use App\Http\Resources\UserCouponCollection;
use App\Http\Resources\UserCouponLogCollection;
use App\Http\Resources\UserMissionCollection;
use App\Http\Resources\UserPointLogCollection;
use App\Models\IssuePoint;
use App\Models\PointTransactionLog;
use App\Models\UserCoupon;
use App\Models\UserCouponLog;
use App\Models\UserMission;
use App\Models\UserPointLog;
use Illuminate\Http\Request;

/**
 * Class CustomerLogController
 * @package App\Http\Controllers
 */
class CustomerLogController extends Controller
{
    public function getCoupons(Request $request)
    {
        $user = $request->user();
        $coupons = UserCoupon::query()->with(['customer'])->whereBelongsTo($user, 'user')->limitOffset(request()->all())
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
        $missions = UserMission::query()->with(['customer'])->whereBelongsTo($user, 'user')->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserMissionCollection($missions),
        ], 200);
    }

    public function getIssuePoints(Request $request)
    {
        $user = $request->user();
        $issuePoints = IssuePoint::query()->with(['customer'])->whereBelongsTo($user, 'user')->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new IssuePointCollection($issuePoints),
        ], 200);
    }

    public function getPointLogs(Request $request)
    {
        $user = $request->user();
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = UserPointLog::query()
            ->with(['userPoint' => function ($q) {
                $q->with(['issuePoint' => function ($q) {
                    $q->with(['userMission' => function ($q) {
                        $q->with('bannerPromotion');
                    }]);
                }]);
            }])
            ->with(['userCoupon' => function ($query) {
                $query->with('bannerProduct');
            }])
            ->whereHas('userPoint', function ($query) use ($user) {
                $query->with(['customer'])->whereBelongsTo($user, 'user');
            });

        if ($startDate && $endDate)
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $pointLogs = $query->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserPointLogCollection($pointLogs),
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
                $query->with('bannerProduct');
            }])
            ->whereHas('userCoupon', function ($query) use ($user) {
                $query->with(['customer'])->whereBelongsTo($user, 'user');
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

    public function getPointTransactionLogs(Request $request)
    {
        $user = $request->user();
        $post = request()->all();
        $startDateString = $post['start_date'] ?? null;
        $endDateString = $post['end_date'] ?? null;
        $startDate = $startDateString ? \Carbon\Carbon::parse($startDateString) : null;
        $endDate = $endDateString ? \Carbon\Carbon::parse($endDateString) : null;

        $query = PointTransactionLog::query();

        $query->whereBelongsTo($user, 'user');

        if ($startDate && $endDate)
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $pointTransactionLogs = $query->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new PointTransactionLogCollection($pointTransactionLogs),
        ], 200);
    }
}
