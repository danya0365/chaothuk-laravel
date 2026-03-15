<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class UserImageService
{
    /**
     * Handle the avatar file upload.
     * Serves as the single source of truth for storing user avatar images.
     */
    public function handleAvatarUpload(UploadedFile $file, ?User $user = null): array
    {
        try {
            if ($user) {
                // If user context is provided, clean up old user-specific directory and store there
                Storage::disk('public')->deleteDirectory("users/{$user->id}/avatar");
                $path = $file->store("users/{$user->id}/avatar", 'public');
            } else {
                // Fallback for pre-registration uploads (not attached to a specific user yet)
                $date = \Carbon\Carbon::now()->format('Y-m-d');
                $random = Str::random(6);
                $name = 'avatar.' . $file->extension();
                $path = $file->storeAs("images/{$date}/{$random}", $name, 'public');
            }
            
            $absolutePath = Storage::disk('public')->path($path);
            
            Image::imagick()->read($absolutePath)
                ->coverDown(512, 512)
                ->save($absolutePath);
            
            return [
                'status' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'avatar' => asset('storage/' . $path),
                ],
                'path' => $path,
                'url' => asset('storage/' . $path)
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle the cover file upload.
     * Serves as the single source of truth for storing user cover images.
     */
    public function handleCoverUpload(UploadedFile $file, ?User $user = null): array
    {
        try {
            if ($user) {
                // If user context is provided, clean up old user-specific directory and store there
                Storage::disk('public')->deleteDirectory("users/{$user->id}/cover");
                $path = $file->store("users/{$user->id}/cover", 'public');
            } else {
                // Fallback for pre-registration uploads
                $date = \Carbon\Carbon::now()->format('Y-m-d');
                $random = Str::random(6);
                $name = 'cover.' . $file->extension();
                $path = $file->storeAs("images/{$date}/{$random}", $name, 'public');
            }
            
            return [
                'status' => true,
                'message' => 'Cover uploaded successfully',
                'data' => [
                    'cover' => asset('storage/' . $path),
                ],
                'path' => $path,
                'url' => asset('storage/' . $path)
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle banner image upload
     */
    public function handleBannerUpload(UploadedFile $file): array
    {
        try {
            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $name = 'banner_' . $random . '.' . $file->extension();
            $path = $file->storeAs("banners/{$date}", $name, 'public');
            
            return [
                'status' => true,
                'message' => 'Banner uploaded successfully',
                'url' => asset('storage/' . $path),
                'path' => $path
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle category image upload
     */
    public function handleCategoryUpload(UploadedFile $file): array
    {
        try {
            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $name = 'category_' . $random . '.' . $file->extension();
            $path = $file->storeAs("categories/{$date}", $name, 'public');
            
            return [
                'status' => true,
                'message' => 'Category image uploaded successfully',
                'url' => asset('storage/' . $path),
                'path' => $path
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle work type image upload
     */
    public function handleWorkTypeUpload(UploadedFile $file): array
    {
        try {
            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $name = 'work_type_' . $random . '.' . $file->extension();
            $path = $file->storeAs("work_types/{$date}", $name, 'public');
            
            return [
                'status' => true,
                'message' => 'Work Type image uploaded successfully',
                'url' => asset('storage/' . $path),
                'path' => $path
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle generic document upload
     */
    public function handleDocumentUpload(UploadedFile $document): array
    {
        try {
            $documentExtension = $document->extension();
            $originalFileName = 'original.' . $documentExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "documents/{$date}/{$random}";
            $documentPath = $document->storeAs($storeOriginalFileDir, $originalFileName, 'public');

            return [
                'status' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'original' => asset('storage/' . $documentPath),
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle original image upload only
     */
    public function handleOriginalImageUpload(UploadedFile $photo): array
    {
        try {
            $photoExtension = $photo->extension();
            $originalFileName = 'original.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "images/{$date}/{$random}";
            $photoPath = $photo->storeAs($storeOriginalFileDir, $originalFileName, 'public');

            return [
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'original' => asset('storage/' . $photoPath),
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Handle image upload and generate a resized version
     */
    public function handleImageUpload(UploadedFile $photo): array
    {
        try {
            $photoExtension = $photo->extension();
            $originalFileName = 'original.' . $photoExtension;
            $resizeFileName = 'resize_w800.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "images/{$date}/{$random}";
            
            $photoPath = $photo->storeAs($storeOriginalFileDir, $originalFileName, 'public');

            $absolutePath = Storage::disk('public')->path($photoPath);
            $resizePath = $storeOriginalFileDir . '/' . $resizeFileName;
            $absoluteResizePath = Storage::disk('public')->path($resizePath);

            Storage::disk('public')->makeDirectory(dirname($resizePath));

            Image::imagick()->read($absolutePath)
                ->scaleDown(2000, 2000)
                ->save($absolutePath);

            Image::imagick()->read($absolutePath)
                ->scaleDown(800, 800)
                ->save($absoluteResizePath);

            return [
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'original' => asset('storage/' . $photoPath),
                    'resize' => asset('storage/' . $resizePath)
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }
}
