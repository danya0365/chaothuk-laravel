<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BannerProductController;
use App\Http\Controllers\Api\BannerPromotionController;
use App\Http\Controllers\Api\BarcodeController;
use App\Http\Controllers\Api\BarcodePreviewController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\CustomerLogController;
use App\Http\Controllers\Api\IssuePointController;
use App\Http\Controllers\Api\MerchantLogController;
use App\Http\Controllers\Api\MessengerController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\UserCouponController;
use App\Http\Controllers\Api\UserMissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'me', 'as' => 'api.me.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('index');
    Route::post('/update-theme', [ProfileController::class, 'updateTheme'])->name('update-theme');
});

Route::group(['prefix' => 'upload', 'as' => 'api.upload.'], function () {
    Route::post('/document', [UploadController::class, 'doUploadDocument'])->name('document');
    Route::post('/image', [UploadController::class, 'doUploadImage'])->name('image');
    Route::post('/original-image', [UploadController::class, 'doUploadOriginalImage'])->name('image');
    Route::post('/avatar', [UploadController::class, 'doUploadAvatar'])->name('avatar');
});

Route::group(['prefix' => 'configurations', 'as' => 'api.configurations.'], function () {
    Route::get('/', [ConfigurationController::class, 'all'])->name('all');
});

Route::group(['prefix' => 'provinces', 'as' => 'api.provinces.'], function () {
    Route::get('/', [ProvinceController::class, 'all'])->name('all');
});

Route::group(['prefix' => 'tags', 'as' => 'api.tags.'], function () {
    Route::get('/', [TagController::class, 'all'])->name('all');
    Route::get('/products', [TagController::class, 'products'])->name('products');
    Route::get('/promotions', [TagController::class, 'promotions'])->name('promotions');
});

Route::group(['prefix' => 'notifications', 'as' => 'api.notifications.'], function () {
    Route::get('/', [NotificationController::class, 'list'])->name('list');
    Route::get('/last-update', [NotificationController::class, 'lastUpdate'])->name('last-update');
    Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'banners', 'as' => 'api.banners.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [BannerController::class, 'list'])->name('list');
    Route::get('/pinned', [BannerController::class, 'pinnedBanners'])->name('pinned-banners');
    Route::get('/last-update', [BannerController::class, 'lastUpdate'])->name('last-update');

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [BannerController::class, 'bannerProducts'])->name('list');
        Route::get('/{id}/user-coupons', [BannerController::class, 'listUserCoupons'])->name('user-coupons.list');
        Route::post('/{id}/user-coupons', [BannerController::class, 'storeUserCoupon'])->name('user-coupons.store');
    });

    Route::group(['prefix' => 'promotions', 'as' => 'promotions.'], function () {
        Route::get('/', [BannerController::class, 'bannerPromotions'])->name('list');
        Route::get('/{id}/user-missions', [BannerController::class, 'listUserMissions'])->name('user-missions.list');
        Route::post('/{id}/user-missions', [BannerController::class, 'storeUserMission'])->name('user-missions.store');
    });

    Route::get('/{id}', [BannerController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'user-missions', 'as' => 'api.user-missions.'], function () {
    Route::get('/{id}', [UserMissionController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'banner-products', 'as' => 'api.banner-products.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [BannerProductController::class, 'bannerProducts'])->name('list');
    Route::get('/{id}', [BannerProductController::class, 'show'])->name('show');
    Route::get('/code/{code}', [BannerProductController::class, 'searchByCode'])->name('code');
});

Route::group(['prefix' => 'banner-promotions', 'as' => 'api.banner-promotions.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [BannerPromotionController::class, 'bannerPromotions'])->name('list');
    Route::get('/{id}', [BannerPromotionController::class, 'show'])->name('show');
    Route::get('/code/{code}', [BannerProductController::class, 'searchByCode'])->name('code');
});

Route::group(['prefix' => 'user-coupons', 'as' => 'api.user-coupons.'], function () {
    Route::get('/{id}', [UserCouponController::class, 'show'])->name('show');
    Route::get('/code/{code}', [UserCouponController::class, 'searchByCode'])->name('code');
    Route::post('/use', [UserCouponController::class, 'use'])->name('use');
});


Route::group(['prefix' => 'issue-points', 'as' => 'api.issue-points.'], function () {
    Route::get('/{id}', [IssuePointController::class, 'show'])->name('show');
});


Route::group(['prefix' => 'barcode', 'as' => 'api.barcode.'], function () {
    Route::get('/qr/{code}', [BarcodeController::class, 'qr'])->name('qr');
    Route::get('/code128/{code}', [BarcodeController::class, 'code128'])->name('code128');
    Route::get('/code128a/{code}', [BarcodeController::class, 'code128a'])->name('code128a');
    Route::get('/code128b/{code}', [BarcodeController::class, 'code128b'])->name('code128b');
    Route::get('/code128c/{code}', [BarcodeController::class, 'code128c'])->name('code128c');
});

Route::group(['prefix' => 'barcode-preview', 'as' => 'api.barcode-preview.'], function () {
    Route::get('/qr/{code}', [BarcodePreviewController::class, 'qr'])->name('qr');
    Route::get('/code128/{code}', [BarcodePreviewController::class, 'code128'])->name('code128');
    Route::get('/code128a/{code}', [BarcodePreviewController::class, 'code128a'])->name('code128a');
    Route::get('/code128b/{code}', [BarcodePreviewController::class, 'code128b'])->name('code128b');
    Route::get('/code128c/{code}', [BarcodePreviewController::class, 'code128c'])->name('code128c');
});

Route::group(['prefix' => 'messenger', 'as' => 'api.messenger.'], function () {
    Route::post('/telephone-channel/new', [MessengerController::class, 'newTelephoneChannel'])->name('telephone-channel.new');
    Route::get('/telephone-channel/{id}/{telephone}/conversations', [MessengerController::class, 'getTelephoneChannelConversations'])->name('telephone-channel.conversations');
    Route::get('/telephone-channel/{id}/{telephone}/conversations/last', [MessengerController::class, 'getLastTelephoneChannelConversations'])->name('telephone-channel.conversations.last');
    Route::post('/telephone-channel/{id}/{telephone}/conversations', [MessengerController::class, 'storeTelephoneChannelConversations'])->name('telephone-channel.conversations.store');
    Route::post('/channel/{id}/conversations/{conversationId}/seen', [MessengerController::class, 'updateSeenAtInConversations'])->name('channel.conversations.seen');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/channel/me', [MessengerController::class, 'getMyChannel'])->name('channel.me');
        Route::get('/channel/{channelId}/conversations', [MessengerController::class, 'getChannelConversations'])->name('channel.conversations');
        Route::post('/channel/{channelId}/conversations', [MessengerController::class, 'storeChannelConversations'])->name('channel.conversations.store');
        Route::get('/channel/{channelId}/conversations/last', [MessengerController::class, 'getLastChannelConversations'])->name('channel.conversations.last');
    });
});

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
});

Route::group(['prefix' => 'customer-logs', 'as' => 'api.customer-logs.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/point-logs', [CustomerLogController::class, 'getPointLogs'])->name('point-logs');
    Route::get('/coupon-logs', [CustomerLogController::class, 'getCouponLogs'])->name('coupon-logs');
    Route::get('/missions', [CustomerLogController::class, 'getMissions'])->name('missions');
    Route::get('/point-transaction-logs', [CustomerLogController::class, 'getPointTransactionLogs'])->name('point-transaction-logs');
});

Route::group(['prefix' => 'merchant-logs', 'as' => 'api.merchant-logs.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/coupon-logs', [MerchantLogController::class, 'getCouponLogs'])->name('coupon-logs');
    Route::get('/missions', [MerchantLogController::class, 'getMissions'])->name('missions');
});

Route::group(['prefix' => 'auth', 'as' => 'api.auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/customer-register-login', [AuthController::class, 'customerRegisterLogin'])->name('customer-register-login');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/revoke-token', [AuthController::class, 'revokeToken'])->name('revokeToken');
    });
});
