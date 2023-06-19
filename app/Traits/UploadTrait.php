<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

trait UploadTrait
{
    public function uploadImage(Request $request, $uploadName = 'image')
    {
        $validatedRequest = Validator::make(
            $request->all(),
            [
                $uploadName => 'required|image|max:2048',
            ]
        );

        if ($validatedRequest->fails()) {
            return [
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ];
        }

        if (!$request->hasFile($uploadName) || !$request->file($uploadName)->isValid()) {
            return [
                'status' => false,
                'message' => 'validation error',
                'errors' => 'file not found'
            ];
        }

        try {
            $photo = $request->file($uploadName);
            //$fileName = $photo->getClientOriginalName();
            //$fileName = str_replace(' ', '_', $fileName);
            $photoExtension = $photo->getClientOriginalExtension();
            $originalFileName = 'original.' . $photoExtension;
            $resizeFileName = 'resize_w800.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "$date/$random/$originalFileName";
            $storeResizeFileDir = "$date/$random/$resizeFileName";
            $photoPath = $photo->storeAs('images', $storeOriginalFileDir);

            $resizedPhoto = Image::make($photoPath)
                ->resize(2000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save();

            $resizedPhoto = Image::make($photoPath)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
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

    public function uploadAvatar(Request $request, $uploadName = 'avatar')
    {
        $validatedRequest = Validator::make(
            $request->all(),
            [
                $uploadName => 'required|image|max:2048',
            ]
        );

        if ($validatedRequest->fails()) {
            return [
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ];
        }

        if (!$request->hasFile($uploadName) || !$request->file($uploadName)->isValid()) {
            return [
                'status' => false,
                'message' => 'validation error',
                'errors' => 'file not found'
            ];
        }

        try {
            $photo = $request->file($uploadName);
            $photoExtension = $photo->getClientOriginalExtension();
            $originalFileName = 'avatar.' . $photoExtension;

            $date = \Carbon\Carbon::now()->format('Y-m-d');
            $random = Str::random(6);
            $storeOriginalFileDir = "$date/$random/$originalFileName";
            $photoPath = $photo->storeAs('images', $storeOriginalFileDir);

            $resizedPhoto = Image::make($photoPath)
                ->resize(512, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save();

            return [
                'status' => true,
                'message' => 'Photo uploaded successfully',
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
