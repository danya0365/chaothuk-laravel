<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IssuePointResource;
use App\Http\Resources\UserMissionResource;
use App\Models\IssuePoint;
use App\Models\UserMission;

/**
 * Class IssuePointController
 * @package App\Http\Controllers
 */
class IssuePointController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $issuePoint = IssuePoint::with(['userMission' => function ($q) {
            $q->with('bannerPromotion');
        }])->find($id);

        return response()->json([
            'status' => $issuePoint ? true : false,
            'data' => new IssuePointResource($issuePoint),
        ], 200);
    }
}
