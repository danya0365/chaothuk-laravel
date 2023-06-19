<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    use UploadTrait;

    public function doUploadImage(Request $request)
    {
        $uploadResult = $this->uploadImage($request, 'image');
        if ($uploadResult['status']) {
            return response()->json($uploadResult, 200);
        } else {
            return response()->json($uploadResult, 500);
        }
    }

    public function doUploadAvatar(Request $request)
    {
        $uploadResult = $this->uploadAvatar($request, 'avatar');
        if ($uploadResult['status']) {
            return response()->json($uploadResult, 200);
        } else {
            return response()->json($uploadResult, 500);
        }
    }
}
