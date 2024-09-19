<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MessengerController;
use App\Http\Controllers\Api\UploadController;

Route::group(['prefix' => 'me', 'as' => 'ajax.me.', 'middleware' => ['auth']], function () {
    Route::post('/update-theme', [ProfileController::class, 'updateTheme'])->name('update-theme');
});

Route::group(['prefix' => 'messenger', 'as' => 'ajax.messenger.', 'middleware' => ['auth']], function () {
    Route::get('/channel/{channelId}/conversations', [MessengerController::class, 'getChannelConversations'])->name('channel.conversations');
    Route::post('/channel/{channelId}/conversations', [MessengerController::class, 'storeChannelConversations'])->name('channel.conversations.store');
});

Route::group(['prefix' => 'messenger', 'as' => 'ajax.messenger.'], function () {
    Route::post('/mobilephone-channel/new', [MessengerController::class, 'newMobilePhoneChannel'])->name('mobilephone-channel.new');
    Route::get('/mobilephone-channel/{channelId}/{mobilePhone}/conversations', [MessengerController::class, 'getMobilePhoneChannelConversations'])->name('mobilephone-channel.conversations');
    Route::post('/mobilephone-channel/{channelId}/{mobilePhone}/conversations', [MessengerController::class, 'storeMobilePhoneChannelConversations'])->name('mobilephone-channel.conversations.store');
    Route::post('/channel/{id}/conversations/{conversationId}/seen', [MessengerController::class, 'updateSeenAtInConversations'])->name('channel.conversations.seen');
});

Route::group(['prefix' => 'upload', 'as' => 'ajax.upload.'], function () {
    Route::post('/document', [UploadController::class, 'doUploadDocument'])->name('document');
    Route::post('/image', [UploadController::class, 'doUploadImage'])->name('image');
    Route::post('/avatar', [UploadController::class, 'doUploadAvatar'])->name('avatar');
});
