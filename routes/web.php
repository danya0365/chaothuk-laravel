<?php

use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BannerProductController;
use App\Http\Controllers\Backend\BannerPromotionController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\IssuePointController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\MessengerChannelController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\Backend\MessengerController as BackendMessengerController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\UserBackendController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserCouponController;
use App\Http\Controllers\Backend\UserCustomerController;
use App\Http\Controllers\Backend\UserMerchantController;
use App\Http\Controllers\Backend\UserMissionController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\BarcodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('messenger/telephone-channels/new', [MessengerController::class, 'newTelephoneChannel'])->name('messenger.telephone-channel.new');
Route::post('messenger/telephone-channels/register', [MessengerController::class, 'registerTelephoneChannel'])->name('messenger.telephone-channel.register');
Route::get('messenger/telephone-channels/{id}/{telephone}', [MessengerController::class, 'getTelephoneChannel'])->name('messenger.telephone-channel');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'IsCanAccessBackend']], function () {
    Route::get('/', [BackendController::class, 'index'])->name('index');
    Route::resource('users', UserController::class);
    Route::resource('user-customers', UserCustomerController::class);
    Route::get('user-customers/{id}/coupons', [UserCustomerController::class, 'getCoupons'])->name('user-customers.coupons');
    Route::get('user-customers/{id}/missions', [UserCustomerController::class, 'getMissions'])->name('user-customers.missions');
    Route::get('user-customers/{id}/issue-points', [UserCustomerController::class, 'getIssuePoints'])->name('user-customers.issue-points');
    Route::get('user-customers/{id}/point-logs', [UserCustomerController::class, 'getPointLogs'])->name('user-customers.point-logs');
    Route::get('user-customers/{id}/coupon-logs', [UserCustomerController::class, 'getCouponLogs'])->name('user-customers.coupon-logs');

    Route::resource('user-merchants', UserMerchantController::class);
    Route::get('user-merchants/{id}/banner-products', [UserMerchantController::class, 'getBannerProducts'])->name('user-merchants.banner-products');
    Route::get('user-merchants/{id}/banner-promotions', [UserMerchantController::class, 'getBannerPromotions'])->name('user-merchants.banner-promotions');

    Route::resource('user-backends', UserBackendController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('messenger-channels', MessengerChannelController::class);
    Route::resource('configurations', ConfigurationController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('banner-products', BannerProductController::class);
    Route::resource('banner-promotions', BannerPromotionController::class);

    Route::resource('user-coupons', UserCouponController::class);
    Route::get('user-missions/in-progress-list', [UserMissionController::class, 'getInProgressList'])->name('user-missions.in-progress-list');
    Route::resource('user-missions', UserMissionController::class);
    Route::get('user-missions/{id}/status-form', [UserMissionController::class, 'showStatusForm'])->name('user-missions.status-form');
    Route::any('user-missions/{id}/status-submit', [UserMissionController::class, 'submitStatusForm'])->name('user-missions.status-submit');

    Route::get('messenger/channels/{id}', [BackendMessengerController::class, 'getChannel'])->name('messenger.channel');

    Route::any('issue-points/schedule-list', [IssuePointController::class, 'scheduleList'])->name('issue-points.schedule-list');
    Route::resource('issue-points', IssuePointController::class);
    Route::get('issue-points/{id}/status-form', [IssuePointController::class, 'showStatusForm'])->name('issue-points.status-form');
    Route::any('issue-points/{id}/status-submit', [IssuePointController::class, 'submitStatusForm'])->name('issue-points.status-submit');

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/point-logs', [ReportController::class, 'getPointLogs'])->name('point-logs');
        Route::get('/issue-points', [ReportController::class, 'getIssuePoints'])->name('issue-points');
        Route::get('/issue-point-status-logs', [ReportController::class, 'getIssuePointStatusLogs'])->name('issue-point-status-logs');
        Route::get('/mission-status-logs', [ReportController::class, 'getMissionStatusLog'])->name('mission-status-logs');
        Route::get('/coupon-logs', [ReportController::class, 'getCouponLogs'])->name('coupon-logs');
        Route::get('/coupon-logs-by-merchant', [ReportController::class, 'getCouponLogsByMerchant'])->name('coupon-logs-by-merchant');
        Route::get('/user-activity-logs', [ReportController::class, 'getUserActivityLogs'])->name('user-activity-logs');
        Route::get('/cron-logs', [ReportController::class, 'getCronLogs'])->name('cron-logs');
        Route::get('/point-transaction-logs', [ReportController::class, 'getPointTransactionLogs'])->name('point-transaction-logs');
    });
});

Route::group(['prefix' => 'barcode', 'as' => 'barcode.'], function () {
    Route::get('/qr/{code}', [BarcodeController::class, 'qr'])->name('qr');
    Route::get('/code128/{code}', [BarcodeController::class, 'code128'])->name('code128');
});

require __DIR__ . '/auth.php';
