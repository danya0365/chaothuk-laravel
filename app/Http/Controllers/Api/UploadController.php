<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Requests\UploadDocumentRequest;
use App\Http\Requests\UploadImageRequest;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    use UploadTrait;

    public function doUploadDocument(UploadDocumentRequest $request)
    {
        Log::info(print_r($request->all(), true));
        $post = $request->validated();
        $uploadResult = $this->uploadDocument($post['document']);
        if ($uploadResult['status']) {
            return response()->json($uploadResult, 200);
        } else {
            return response()->json($uploadResult, 401);
        }
    }

    public function doUploadImage(UploadImageRequest $request)
    {
        Log::info(print_r($request->all(), true));
        $post = $request->validated();
        $uploadResult = $this->uploadImage($post['image']);
        if ($uploadResult['status']) {
            return response()->json($uploadResult, 200);
        } else {
            return response()->json($uploadResult, 401);
        }
    }

    public function doUploadOriginalImage(UploadImageRequest $request)
    {
        Log::info(print_r($request->all(), true));
        $post = $request->validated();
        $uploadResult = $this->uploadOriginalImage($post['image']);
        if ($uploadResult['status']) {
            return response()->json($uploadResult, 200);
        } else {
            return response()->json($uploadResult, 401);
        }
    }

    public function doUploadAvatar(UploadAvatarRequest $request)
    {
        Log::info(print_r($request->all(), true));
        $post = $request->validated();
        $uploadResult = $this->uploadAvatar($post['avatar']);
        if ($uploadResult['status']) {
            return response()->json($uploadResult, 200);
        } else {
            return response()->json($uploadResult, 401);
        }
    }
}