<?php

use App\Http\Controllers\GeographyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RecruitBookingController;
use App\Http\Controllers\RecruitController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkBookingController;
use App\Http\Controllers\WorkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);
Route::resource('geographies', GeographyController::class);
Route::resource('province', ProvinceController::class);
Route::resource('works', WorkController::class);
Route::resource('recruits', RecruitController::class);
Route::resource('work-bookings', WorkBookingController::class);
Route::resource('recruit-bookings', RecruitBookingController::class);
Route::resource('review', ReviewController::class);
Route::resource('reply', ReplyController::class);
