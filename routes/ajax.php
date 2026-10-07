<?php

use App\Http\Controllers\Frontend\MessengerController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\UploadController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'me', 'as' => 'ajax.me.', 'middleware' => ['auth']], function () {
    Route::post('/update-theme', [ProfileController::class, 'updateTheme'])->name('update-theme');
});

Route::group(['prefix' => 'messenger', 'as' => 'ajax.messenger.'], function () {
    Route::post('/mobile-phone-channel/new', [MessengerController::class, 'newMobilePhoneChannel'])->name('mobile-phone-channel.new');
    Route::get('/mobile-phone-channel/{channelId}/{mobilePhone}/conversations', [MessengerController::class, 'getMobilePhoneChannelConversations'])->name('mobile-phone-channel.conversations');
    Route::post('/mobile-phone-channel/{channelId}/{mobilePhone}/conversations', [MessengerController::class, 'storeMobilePhoneChannelConversations'])->name('mobile-phone-channel.conversations.store');
    Route::post('/channel/{id}/conversations/{conversationId}/seen', [MessengerController::class, 'updateSeenAtInConversations'])->name('channel.conversations.seen');
});

Route::group(['prefix' => 'upload', 'as' => 'ajax.upload.', 'middleware' => ['auth']], function () {
    Route::post('/document', [UploadController::class, 'doUploadDocument'])->name('document');
    Route::post('/image', [UploadController::class, 'doUploadImage'])->name('image');
    Route::post('/avatar', [UploadController::class, 'doUploadAvatar'])->name('avatar');
});
