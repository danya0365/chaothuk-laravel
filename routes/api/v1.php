<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RecruitBookingController;
use App\Http\Controllers\Api\V1\RecruitController;
use App\Http\Controllers\Api\V1\UploadController;
use App\Http\Controllers\Api\V1\WorkBookingController;
use App\Http\Controllers\Api\V1\WorkController;
use App\Models\Category;
use App\Models\Province;
use App\Models\WorkType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — mobile client
|--------------------------------------------------------------------------
|
| Mounted by bootstrap/app.php under the /api prefix, so every route here is
| /api/v1/... Responses are standard REST: the HTTP code says what happened and
| the body carries data or a Thai error message.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    // Reference data the app needs to build its filter and form dropdowns.
    Route::get('/provinces', fn () => Province::orderBy('name_th')->get(['id', 'code', 'name_th', 'name_en']))
        ->name('provinces.index');

    Route::get('/work-types', fn () => WorkType::orderBy('title')->get(['id', 'title', 'image']))
        ->name('work-types.index');

    Route::get('/categories', fn () => Category::orderBy('name')->get(['id', 'name', 'image_url']))
        ->name('categories.index');

    // Browsing stays public so a signed-out visitor can look around the marketplace.
    Route::prefix('works')->name('works.')->group(function () {
        Route::get('/', [WorkController::class, 'index'])->name('index');
        Route::get('/{work}', [WorkController::class, 'show'])->name('show');
    });

    Route::prefix('recruits')->name('recruits.')->group(function () {
        Route::get('/', [RecruitController::class, 'index'])->name('index');
        Route::get('/{recruit}', [RecruitController::class, 'show'])->name('show');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('uploads')->name('uploads.')->group(function () {
            Route::post('/image', [UploadController::class, 'image'])->name('image');
            Route::post('/avatar', [UploadController::class, 'avatar'])->name('avatar');
        });

        Route::prefix('works')->name('works.')->group(function () {
            Route::post('/', [WorkController::class, 'store'])->name('store');
            Route::post('/{work}/bookings', [WorkBookingController::class, 'store'])->name('bookings.store');
        });

        Route::prefix('recruits')->name('recruits.')->group(function () {
            Route::post('/', [RecruitController::class, 'store'])->name('store');
            Route::post('/{recruit}/bookings', [RecruitBookingController::class, 'store'])->name('bookings.store');
        });

        Route::prefix('work-bookings')->name('work-bookings.')->group(function () {
            Route::get('/', [WorkBookingController::class, 'indexAsCustomer'])->name('index');
            Route::get('/as-owner', [WorkBookingController::class, 'indexAsOwner'])->name('index.as-owner');
            Route::get('/{workBooking}', [WorkBookingController::class, 'show'])->name('show');
            Route::post('/{workBooking}/confirm', [WorkBookingController::class, 'confirmByWorker'])->name('confirm');
            Route::post('/{workBooking}/confirm-as-customer', [WorkBookingController::class, 'confirmByCustomer'])->name('confirm.as-customer');
            Route::post('/{workBooking}/cancel', [WorkBookingController::class, 'cancel'])->name('cancel');
        });

        Route::prefix('recruit-bookings')->name('recruit-bookings.')->group(function () {
            Route::get('/', [RecruitBookingController::class, 'indexAsApplicant'])->name('index');
            Route::get('/as-employer', [RecruitBookingController::class, 'indexAsEmployer'])->name('index.as-employer');
            Route::get('/{recruitBooking}', [RecruitBookingController::class, 'show'])->name('show');
            Route::post('/{recruitBooking}/confirm', [RecruitBookingController::class, 'confirmByEmployer'])->name('confirm');
            Route::post('/{recruitBooking}/confirm-as-applicant', [RecruitBookingController::class, 'confirmByApplicant'])->name('confirm.as-applicant');
            Route::post('/{recruitBooking}/cancel', [RecruitBookingController::class, 'cancel'])->name('cancel');
        });
    });
});