<?php

use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\UploadController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'me', 'as' => 'ajax.me.', 'middleware' => ['auth']], function () {
    Route::post('/update-theme', [ProfileController::class, 'updateTheme'])->name('update-theme');
});

Route::group(['prefix' => 'upload', 'as' => 'ajax.upload.', 'middleware' => ['auth']], function () {
    Route::post('/document', [UploadController::class, 'doUploadDocument'])->name('document');
    Route::post('/image', [UploadController::class, 'doUploadImage'])->name('image');
    Route::post('/avatar', [UploadController::class, 'doUploadAvatar'])->name('avatar');
});
