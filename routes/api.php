<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RecruitController;
use App\Http\Controllers\Api\WorkController;

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
    Route::get('recruits', [RecruitController::class, 'getRecruits']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/me', function (Request $request) {
            return $request->user();
        });

        Route::post('works', [WorkController::class, 'createWork']);
        Route::post('recruits', [RecruitController::class, 'createRecruit']);
    });
});
