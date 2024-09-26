<?php

namespace App\Traits;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

trait UploadTrait
{
    public function uploadDocument($document)
    {
        try {
            $documentExtension = $document->extension();
            $originalFileName = 'original.' . $documentExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "$date/$random/$originalFileName";
            $documentPath = $document->storeAs('documents', $storeOriginalFileDir);

            return [
                'status' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'original' => asset($documentPath),
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public function uploadOriginalImage($photo)
    {
        try {
            $photoExtension = $photo->extension();
            $originalFileName = 'original.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "$date/$random/$originalFileName";
            $photoPath = $photo->storeAs('images', $storeOriginalFileDir);

            return [
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'original' => asset($photoPath),
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public function uploadImage($photo)
    {
        try {
            $photoExtension = $photo->extension();
            $originalFileName = 'original.' . $photoExtension;
            $resizeFileName = 'resize_w800.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "$date/$random/$originalFileName";
            $storeResizeFileDir = "$date/$random/$resizeFileName";
            $photoPath = $photo->storeAs('images', $storeOriginalFileDir);

            Image::imagick()->read($photoPath)
                ->scaleDown(2000, 2000)
                ->save();

            Image::imagick()->read($photoPath)
                ->scaleDown(800, 800)
                ->save('images/' . $storeResizeFileDir);

            return [
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'original' => asset($photoPath),
                    'resize' => asset('images/' . $storeResizeFileDir)
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    public function uploadAvatar($photo)
    {
        try {
            $photoExtension = $photo->extension();
            $originalFileName = 'avatar.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "$date/$random/$originalFileName";
            $photoPath = $photo->storeAs('images', $storeOriginalFileDir);

            Image::imagick()->read($photoPath)
                ->coverDown(512, 512)
                ->save();

            return [
                'status' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'avatar' => asset($photoPath),
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
