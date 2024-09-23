<?php

use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\IssuePointController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\MessengerChannelController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\Backend\MessengerController as BackendMessengerController;
use App\Http\Controllers\Backend\RecruitController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WorkController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\BarcodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('messenger/mobile-phone-channels/new', [MessengerController::class, 'newMobilePhoneChannel'])->name('messenger.mobile-phone-channel.new');
Route::post('messenger/mobile-phone-channels/register', [MessengerController::class, 'registerMobilePhoneChannel'])->name('messenger.mobile-phone-channel.register');
Route::get('messenger/mobile-phone-channels/{id}/{mobilePhone}', [MessengerController::class, 'getMobilePhoneChannel'])->name('messenger.mobile-phone-channel');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'IsCanAccessBackend']], function () {
    Route::get('/', [BackendController::class, 'index'])->name('index');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::resource('notifications', NotificationController::class);
    Route::resource('messenger-channels', MessengerChannelController::class);
    Route::resource('configurations', ConfigurationController::class);
    Route::resource('banners', BannerController::class);

    Route::resource('works', WorkController::class);
    Route::resource('recruits', RecruitController::class);

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
