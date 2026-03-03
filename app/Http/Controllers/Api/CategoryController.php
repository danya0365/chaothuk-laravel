<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * GET /api/categories — รายการหมวดหมู่ทั้งหมด (public)
     */
    public function all()
    {
        $data = Category::orderBy('name', 'asc')->get();
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }
}
