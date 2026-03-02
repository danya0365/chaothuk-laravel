<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BarcodeController;
use App\Http\Controllers\Api\BarcodePreviewController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\GeographyController;
use App\Http\Controllers\Api\UserLogController;
use App\Http\Controllers\Api\IssuePointController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\MessengerController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\RecruitBookingController;
use App\Http\Controllers\Api\RecruitController;
use App\Http\Controllers\Api\SubDistrictController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\WorkBookingController;
use App\Http\Controllers\Api\WorkController;
use App\Http\Controllers\Api\WorkTypeController;
use Illuminate\Support\Facades\Artisan;
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
    Route::get('/', [MeController::class, 'getMe']);
    Route::post('/', [MeController::class, 'updateMe']);
    Route::post('/password', [MeController::class, 'updatePassword']);
    Route::get('/notifications', [MeController::class, 'getUserNotifications']);
    Route::get('/works', [MeController::class, 'getWorks']);
    Route::get('/work-likes', [MeController::class, 'getLikeWork']);
    Route::get('/work-likes/work/{workId}', [MeController::class, 'getIsLikeWork']);
    Route::get('/work-bookings', [MeController::class, 'getWorkBookings']);
    Route::get('/recruits', [MeController::class, 'getRecruits']);
    Route::get('/recruit-bookings', [MeController::class, 'getRecruitBookings']);
    Route::get('/posts', [MeController::class, 'getPosts']);
    Route::get('/post-likes', [MeController::class, 'getLikedPosts']);
    Route::get('/post-likes/post/{postId}', [MeController::class, 'getIsLikePost']);
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

Route::group(['prefix' => 'geographies', 'as' => 'api.geographies.'], function () {
    Route::get('/', [GeographyController::class, 'all'])->name('all');
});

Route::group(['prefix' => 'provinces', 'as' => 'api.provinces.'], function () {
    Route::get('/', [ProvinceController::class, 'all'])->name('all');
});

Route::group(['prefix' => 'districts', 'as' => 'api.districts.'], function () {
    Route::get('/{provinceId}', [DistrictController::class, 'byProvince'])->name('by-province');
});

Route::group(['prefix' => 'sub-districts', 'as' => 'api.sub-districts.'], function () {
    Route::get('/{districtId}', [SubDistrictController::class, 'byDistrict'])->name('by-district');
});

Route::group(['prefix' => 'categories', 'as' => 'api.categories.'], function () {
    Route::get('/', [CategoryController::class, 'all'])->name('all');
});

Route::group(['prefix' => 'works', 'as' => 'api.works.'], function () {
    Route::get('/', [WorkController::class, 'getWorks']);
    Route::get('/top-hits', [WorkController::class, 'getTopHits'])->name('/top-hits');
    Route::get('/{workId}', [WorkController::class, 'getWork']);
    Route::get('/{workId}/likes', [WorkController::class, 'getWorkLikes']);
    Route::get('/{workId}/likes/count', [WorkController::class, 'getWorkLikeCount']);
    Route::get('/{workId}/bookings', [WorkController::class, 'getWorkBookings']);
    Route::get('/{workId}/confirm-bookings', [WorkController::class, 'getConfirmWorkBookings']);
    Route::get('/{workId}/reviews', [WorkController::class, 'getWorkReviews']);
    Route::get('/{workId}/reviews/count', [WorkController::class, 'getWorkReviewCount']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/', [WorkController::class, 'createWork']);
        Route::post('/{workId}/bookings', [WorkController::class, 'createWorkBooking']);
        Route::post('/{workId}/likes', [WorkController::class, 'createWorkLike']);
        Route::post('/{workId}/reviews', [WorkController::class, 'createWorkReview']);
    });
});

Route::group(['prefix' => 'work-types', 'as' => 'api.work-types.'], function () {
    Route::get('/', [WorkTypeController::class, 'getWorkTypes']);
});

Route::group(['prefix' => 'work-bookings', 'as' => 'api.work-bookings.'], function () {
    Route::get('/{id}', [WorkBookingController::class, 'show']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/{workBookingId}/worker-confirm', [WorkBookingController::class, 'doWorkerConfirm']);
        Route::post('/{workBookingId}/customer-confirm', [WorkBookingController::class, 'doCustomerConfirm']);
    });
});

Route::group(['prefix' => 'recruits', 'as' => 'api.recruits.'], function () {
    Route::get('/', [RecruitController::class, 'getRecruits']);
    Route::get('/{recruitId}', [RecruitController::class, 'getRecruit']);
    Route::get('/{recruitId}/bookings', [RecruitController::class, 'getRecruitBookings']);
    Route::get('/{recruitId}/reviews', [RecruitController::class, 'getRecruitReviews']);
    Route::get('/{recruitId}/reviews/count', [RecruitController::class, 'getRecruitReviewCount']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/', [RecruitController::class, 'createRecruit']);
        Route::post('/{recruitId}/bookings', [RecruitController::class, 'createRecruitBooking']);
        Route::post('/{recruitId}/reviews', [RecruitController::class, 'createRecruitReview']);
    });
});

Route::group(['prefix' => 'recruit-bookings', 'as' => 'api.recruit-bookings.'], function () {
    Route::get('/{id}', [RecruitBookingController::class, 'show']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/{recruitBookingId}/worker-confirm', [RecruitBookingController::class, 'doWorkerConfirm']);
        Route::post('/{recruitBookingId}/customer-confirm', [RecruitBookingController::class, 'doCustomerConfirm']);
    });
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

    Route::get('/{id}', [BannerController::class, 'show'])->name('show');
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
    Route::post('/mobile-phone-channel/new', [MessengerController::class, 'newMobilePhoneChannel'])->name('mobile-phone-channel.new');
    Route::get('/mobile-phone-channel/{id}/{mobilePhone}/conversations', [MessengerController::class, 'getMobilePhoneChannelConversations'])->name('mobile-phone-channel.conversations');
    Route::get('/mobile-phone-channel/{id}/{mobilePhone}/conversations/last', [MessengerController::class, 'getLastMobilePhoneChannelConversations'])->name('mobile-phone-channel.conversations.last');
    Route::post('/mobile-phone-channel/{id}/{mobilePhone}/conversations', [MessengerController::class, 'storeMobilePhoneChannelConversations'])->name('mobile-phone-channel.conversations.store');
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

Route::group(['prefix' => 'user-logs', 'as' => 'api.customer-logs.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/point-logs', [UserLogController::class, 'getPointLogs'])->name('point-logs');
    Route::get('/point-transaction-logs', [UserLogController::class, 'getPointTransactionLogs'])->name('point-transaction-logs');
});

Route::group(['prefix' => 'posts', 'as' => 'api.posts.'], function () {
    Route::get('/', [PostController::class, 'getPosts'])->name('list');
    Route::get('/{id}', [PostController::class, 'getPost'])->name('show');
    Route::get('/{id}/likes', [PostController::class, 'getPostLikes'])->name('likes');
    Route::get('/{id}/likes/count', [PostController::class, 'getPostLikeCount'])->name('likes.count');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/', [PostController::class, 'createPost'])->name('create');
        Route::post('/{id}', [PostController::class, 'updatePost'])->name('update');
        Route::delete('/{id}', [PostController::class, 'deletePost'])->name('delete');
        Route::post('/{id}/likes', [PostController::class, 'createPostLike'])->name('likes.toggle');
    });
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
