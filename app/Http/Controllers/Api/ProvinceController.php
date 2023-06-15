<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceCollection;
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
        $data = Province::orderBy('id', 'asc')->get();
        return response()->json([
            'status' => true,
            'data' => new ProvinceCollection($data),
        ], 200);
    }
}
