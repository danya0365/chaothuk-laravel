<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubDistrict;

class SubDistrictController extends Controller
{
    /**
     * GET /api/sub-districts/{districtId} — รายการตำบลตามอำเภอ
     */
    public function byDistrict(int $districtId)
    {
        $data = SubDistrict::where('district_id', $districtId)
            ->orderBy('name_th', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
