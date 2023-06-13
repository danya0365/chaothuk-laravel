<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeographyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RecruitBookingController;
use App\Http\Controllers\RecruitController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkBookingController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

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
    return view('home');
})->name('home');

Route::resource('users', UserController::class);
Route::resource('geographies', GeographyController::class);
Route::resource('province', ProvinceController::class);
Route::resource('works', WorkController::class);
Route::resource('recruits', RecruitController::class);
Route::resource('work-bookings', WorkBookingController::class);
Route::resource('recruit-bookings', RecruitBookingController::class);
Route::resource('review', ReviewController::class);
Route::resource('reply', ReplyController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
