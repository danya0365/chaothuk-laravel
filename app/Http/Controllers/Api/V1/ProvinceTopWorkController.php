<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProvinceTopWorkResource;
use App\Models\ProvinceTopWork;
use Illuminate\Http\JsonResponse;

/**
 * The home screen's provincial top-works strip.
 *
 * The web front page shows the current month's rank-1 works per province —
 * the rankings are recalculated monthly by the scheduler, so this endpoint
 * only reads what that job produced; nothing is scored on the fly.
 */
class ProvinceTopWorkController extends Controller
{
    public function index(): JsonResponse
    {
        $topWorks = ProvinceTopWork::query()
            ->currentMonth()
            ->topOnly()
            ->with(['work.author', 'work.workType', 'province'])
            ->orderByDesc('total_score')
            ->get();

        return response()->json([
            'data' => ProvinceTopWorkResource::collection($topWorks),
        ]);
    }
}
