<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
}
