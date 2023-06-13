<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeographyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RecruitBookingController;
use App\Http\Controllers\RecruitController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Supervisor\WorkController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkBookingController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'supervisor', 'as' => 'supervisor.', 'middleware' => ['auth', 'supervisor']], function () {
    Route::get('/', [SupervisorController::class, 'index'])->name('index');

    // Route::group(['prefix' => 'work'], function () {
    //     Route::get('/', [WorkController::class, 'index'])->name('supervisor.work');
    //     Route::resource('posts', PostController::class);
    // });
    Route::resource('works', WorkController::class);
});

require __DIR__ . '/auth.php';
