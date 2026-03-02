<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;

class DistrictController extends Controller
{
    /**
     * GET /api/districts/{provinceId} — รายการอำเภอตามจังหวัด
     */
    public function byProvince(int $provinceId)
    {
        $data = District::where('province_id', $provinceId)
            ->orderBy('name_th', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
