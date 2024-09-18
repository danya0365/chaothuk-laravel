<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserMissionResource;
use App\Models\UserMission;

/**
 * Class UserMissionController
 * @package App\Http\Controllers
 */
class UserMissionController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userMission = UserMission::with('bannerPromotion')->find($id);

        return response()->json([
            'status' => $userMission ? true : false,
            'data' => new UserMissionResource($userMission),
        ], 200);
    }
}
