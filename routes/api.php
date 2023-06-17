<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\RecruitController;
use App\Http\Controllers\Api\WorkController;
use App\Http\Controllers\Api\WorkTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'cors'], function () {
    Route::post('/auth/register', [AuthController::class, 'createUser']);
    Route::post('/auth/login', [AuthController::class, 'loginUser']);
    Route::get('works', [WorkController::class, 'getWorks']);
    Route::get('works/top-hits', [WorkController::class, 'getTopHits'])->name('works/top-hits');

    Route::get('work-types', [WorkTypeController::class, 'getWorkTypes']);
    Route::get('recruits', [RecruitController::class, 'getRecruits']);
    Route::get('provinces', [ProvinceController::class, 'getProvinces']);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::get('me', [MeController::class, 'getMe']);
        Route::post('me', [MeController::class, 'updateMe']);
        Route::post('me/password', [MeController::class, 'updatePassword']);
        Route::get('me/notifications', [MeController::class, 'getUserNotifications']);
        Route::get('me/work-likes', [MeController::class, 'getLikeWork']);
        Route::post('/auth/logout', [AuthController::class, 'logoutUser']);
        Route::post('works', [WorkController::class, 'createWork']);
        Route::post('works/{workId}/likes', [WorkController::class, 'createWorkLike']);
        Route::post('recruits', [RecruitController::class, 'createRecruit']);
        Route::post('upload/photo', [UploadController::class, 'doUploadPhoto']);
        Route::post('upload/avatar', [UploadController::class, 'doUploadAvatar']);
    });
});