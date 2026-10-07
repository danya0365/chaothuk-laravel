<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UploadAvatarRequest;
use App\Http\Requests\Api\V1\UploadImageRequest;
use App\Services\UserImageService;
use Illuminate\Http\JsonResponse;

/**
 * Uploads for the mobile client.
 *
 * Every endpoint here sits behind auth:sanctum and pins the mime type in its
 * FormRequest — the old /api/upload/* routes were public and accepted anything.
 */
class UploadController extends Controller
{
    public function image(UploadImageRequest $request, UserImageService $imageService): JsonResponse
    {
        $result = $imageService->handleImageUpload($request->validated()['image']);

        if (! ($result['status'] ?? false)) {
            return response()->json(['message' => 'อัพโหลดรูปไม่สำเร็จ'], 422);
        }

        return response()->json([
            'data' => [
                'original' => $result['data']['original'] ?? null,
                'resize' => $result['data']['resize'] ?? null,
            ],
        ]);
    }

    public function avatar(UploadAvatarRequest $request, UserImageService $imageService): JsonResponse
    {
        $user = $request->user();

        $result = $imageService->handleAvatarUpload($request->validated()['avatar'], $user);

        if (! ($result['status'] ?? false)) {
            return response()->json(['message' => 'อัพโหลดรูปโปรไฟล์ไม่สำเร็จ'], 422);
        }

        $url = $result['url'] ?? null;

        if ($url) {
            $user->update(['profile_image' => $url]);
        }

        return response()->json([
            'data' => [
                'avatar' => $url,
            ],
        ]);
    }
}
