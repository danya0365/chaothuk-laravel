<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MapRecruitResource;
use App\Http\Resources\Api\V1\MapWorkResource;
use App\Models\Recruit;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Everything with coordinates, for the map screen.
 *
 * The web's /map page reads the same two lists — 500 each — behind a single
 * page load, so the API mirrors it with one public endpoint rather than two
 * filtered paginations. Only listings where the author pinned a location
 * appear; lat/lng-less rows would be anchors in the middle of the ocean.
 */
class MapController extends Controller
{
    /**
     * The cap the web map lives with — 500 pins is past what a phone renders
     * with useful detail, and a hard cap keeps the payload size predictable.
     */
    public const PIN_CAP = 500;

    public function index(Request $request): JsonResponse
    {
        $kind = $request->string('kind')->toString();

        $works = ! in_array($kind, ['recruit'], true) ? Work::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('work_status', 'stand-by')
            ->with(['province', 'workType'])
            ->select(['id', 'title', 'price', 'primary_image', 'latitude', 'longitude', 'province_id', 'work_type_id', 'avg_review_rating'])
            ->orderByDesc('id')
            ->limit(self::PIN_CAP)
            ->get() : collect();

        $recruits = ! in_array($kind, ['work'], true) ? Recruit::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('recruit_status', 'stand-by')
            ->with(['province', 'workType'])
            ->select(['id', 'title', 'budget', 'primary_image', 'latitude', 'longitude', 'province_id', 'work_type_id'])
            ->orderByDesc('id')
            ->limit(self::PIN_CAP)
            ->get() : collect();

        return response()->json([
            'data' => [
                'works' => MapWorkResource::collection($works),
                'recruits' => MapRecruitResource::collection($recruits),
            ],
        ]);
    }
}
