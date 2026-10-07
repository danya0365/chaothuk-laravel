<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Requests\UploadDocumentRequest;
use App\Http\Requests\UploadImageRequest;
use App\Services\UserImageService;
use Illuminate\Support\Facades\Auth;

/**
 * Upload endpoints for the browser session, served under the /ajax prefix.
 *
 * Moved out of the Api namespace when the mobile API was split into its own
 * versioned surface. The mobile app has its own /api/v1/uploads endpoints.
 */
class UploadController extends Controller
{
    public function doUploadDocument(UploadDocumentRequest $request, UserImageService $imageService)
    {
        return $this->respond($imageService->handleDocumentUpload($request->validated()['document']));
    }

    public function doUploadImage(UploadImageRequest $request, UserImageService $imageService)
    {
        return $this->respond($imageService->handleImageUpload($request->validated()['image']));
    }

    public function doUploadAvatar(UploadAvatarRequest $request, UserImageService $imageService)
    {
        $user = Auth::user();
        $result = $imageService->handleAvatarUpload($request->validated()['avatar'], $user);

        if (($result['status'] ?? false) && $user && ($result['url'] ?? null)) {
            $user->update(['profile_image' => $result['url']]);
        }

        return $this->respond($result);
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function respond(array $result)
    {
        return response()->json($result, ($result['status'] ?? false) ? 200 : 500);
    }
}
