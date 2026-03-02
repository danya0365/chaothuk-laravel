<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Geography;

class GeographyController extends Controller
{
    /**
     * GET /api/geographies — รายการภาคทั้งหมด
     */
    public function all()
    {
        $data = Geography::orderBy('id', 'asc')->get();
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
