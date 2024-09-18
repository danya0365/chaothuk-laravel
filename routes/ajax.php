<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MessengerController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\UserCouponController;

Route::group(['prefix' => 'me', 'as' => 'ajax.me.', 'middleware' => ['auth']], function () {
    Route::post('/update-theme', [ProfileController::class, 'updateTheme'])->name('update-theme');
});

Route::group(['prefix' => 'messenger', 'as' => 'ajax.messenger.', 'middleware' => ['auth']], function () {
    Route::get('/channel/{channelId}/conversations', [MessengerController::class, 'getChannelConversations'])->name('channel.conversations');
    Route::post('/channel/{channelId}/conversations', [MessengerController::class, 'storeChannelConversations'])->name('channel.conversations.store');
});

Route::group(['prefix' => 'messenger', 'as' => 'ajax.messenger.'], function () {
    Route::post('/telephone-channel/new', [MessengerController::class, 'newTelephoneChannel'])->name('telephone-channel.new');
    Route::get('/telephone-channel/{channelId}/{telephone}/conversations', [MessengerController::class, 'getTelephoneChannelConversations'])->name('telephone-channel.conversations');
    Route::post('/telephone-channel/{channelId}/{telephone}/conversations', [MessengerController::class, 'storeTelephoneChannelConversations'])->name('telephone-channel.conversations.store');
    Route::post('/channel/{id}/conversations/{conversationId}/seen', [MessengerController::class, 'updateSeenAtInConversations'])->name('channel.conversations.seen');
});

Route::group(['prefix' => 'upload', 'as' => 'ajax.upload.'], function () {
    Route::post('/document', [UploadController::class, 'doUploadDocument'])->name('document');
    Route::post('/image', [UploadController::class, 'doUploadImage'])->name('image');
    Route::post('/avatar', [UploadController::class, 'doUploadAvatar'])->name('avatar');
});

Route::group(['prefix' => 'user-coupons', 'as' => 'ajax.user-coupons.'], function () {
    Route::get('/{id}', [UserCouponController::class, 'show'])->name('show');
    Route::get('/code/{code}', [UserCouponController::class, 'searchByCode'])->name('code');

    Route::group(['middleware' => ['auth']], function () {
        Route::post('/use', [UserCouponController::class, 'backendUse'])->name('use');
    });
});
