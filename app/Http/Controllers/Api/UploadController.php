<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $uploadName = 'photo';
        $validatedRequest = Validator::make(
            $request->all(),
            [
                $uploadName => 'required|image|max:2048',
            ]
        );

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        if (!$request->hasFile($uploadName) || !$request->file($uploadName)->isValid()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => 'file not found'
            ], 401);
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

            return response()->json([
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'original' => asset($photoPath),
                    'resize' => asset('images/' . $storeResizeFileDir)
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function uploadAvatar(Request $request)
    {
        $uploadName = 'photo';
        $validatedRequest = Validator::make(
            $request->all(),
            [
                $uploadName => 'required|image|max:2048',
            ]
        );

        if ($validatedRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatedRequest->errors()
            ], 401);
        }

        if (!$request->hasFile($uploadName) || !$request->file($uploadName)->isValid()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => 'file not found'
            ], 401);
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

            return response()->json([
                'status' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'avatar' => asset($photoPath),
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
