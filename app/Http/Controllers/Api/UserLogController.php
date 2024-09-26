<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IssuePointCollection;
use App\Http\Resources\PointTransactionLogCollection;
use App\Http\Resources\UserPointLogCollection;
use App\Models\IssuePoint;
use App\Models\PointTransactionLog;
use App\Models\UserPointLog;
use Illuminate\Http\Request;

/**
 * Class UserLogController
 * @package App\Http\Controllers
 */
class UserLogController extends Controller
{
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

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $pointLogs = $query->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new UserPointLogCollection($pointLogs),
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

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $pointTransactionLogs = $query->limitOffset(request()->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => new PointTransactionLogCollection($pointTransactionLogs),
        ], 200);
    }
}