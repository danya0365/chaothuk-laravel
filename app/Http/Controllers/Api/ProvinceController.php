<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    /**
     * Get Recruits
     * @param Request $request
     * @return User 
     */
    public function getProvinces(Request $request)
    {
        $data = Province::all();
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
