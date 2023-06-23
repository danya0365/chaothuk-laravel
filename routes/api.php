<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\RecruitController;
use App\Http\Controllers\Api\WorkBookingController;
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
    Route::get('works/{workId}', [WorkController::class, 'getWork']);
    Route::get('works/{workId}/likes', [WorkController::class, 'getWorkLikes']);
    Route::get('works/{workId}/likes/count', [WorkController::class, 'getWorkLikeCount']);
    Route::get('works/{workId}/bookings', [WorkController::class, 'getWorkBooking']);

    Route::get('work-types', [WorkTypeController::class, 'getWorkTypes']);

    Route::get('recruits', [RecruitController::class, 'getRecruits']);
    Route::get('recruits/{recruitId}', [RecruitController::class, 'getRecruit']);

    Route::get('provinces', [ProvinceController::class, 'getProvinces']);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::get('me', [MeController::class, 'getMe']);
        Route::post('me', [MeController::class, 'updateMe']);
        Route::post('me/password', [MeController::class, 'updatePassword']);
        Route::get('me/notifications', [MeController::class, 'getUserNotifications']);
        Route::get('me/works', [MeController::class, 'getWorks']);
        Route::get('me/work-likes', [MeController::class, 'getLikeWork']);
        Route::get('me/work-likes/work/{workId}', [MeController::class, 'getIsLikeWork']);
        Route::get('me/work-bookings', [MeController::class, 'getWorkBookings']);
        Route::get('me/recruits', [MeController::class, 'getRecruits']);
        Route::get('me/recruit-bookings', [MeController::class, 'getRecruitBookings']);
        Route::post('/auth/logout', [AuthController::class, 'logoutUser']);
        Route::post('works', [WorkController::class, 'createWork']);
        Route::post('works/{workId}/bookings', [WorkController::class, 'createWorkBooking']);
        Route::post('works/{workId}/likes', [WorkController::class, 'createWorkLike']);
        Route::post('work-bookings/{workBookingId}/worker-confirm', [WorkBookingController::class, 'doWorkerConfirm']);
        Route::post('work-bookings/{workBookingId}/customer-confirm', [WorkBookingController::class, 'doCustomerConfirm']);
        Route::post('recruits', [RecruitController::class, 'createRecruit']);
        Route::post('upload/image', [UploadController::class, 'doUploadImage']);
        Route::post('upload/avatar', [UploadController::class, 'doUploadAvatar']);
    });
});
