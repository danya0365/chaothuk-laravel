<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AvailabilityController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\V1\MapController;
use App\Http\Controllers\Api\V1\MessengerController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\PushTokenController;
use App\Http\Controllers\Api\V1\RecruitBookingController;
use App\Http\Controllers\Api\V1\RecruitController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\SessionController;
use App\Http\Controllers\Api\V1\UploadController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Controllers\Api\V1\WorkBookingController;
use App\Http\Controllers\Api\V1\WorkController;
use App\Models\Category;
use App\Models\Province;
use App\Models\WorkType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — mobile client
|--------------------------------------------------------------------------
|
| Mounted by bootstrap/app.php under the /api prefix, so every route here is
| /api/v1/... Responses are standard REST: the HTTP code says what happened and
| the body carries data or a Thai error message.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    // Reference data the app needs to build its filter and form dropdowns.
    Route::get('/provinces', fn () => Province::orderBy('name_th')->get(['id', 'code', 'name_th', 'name_en']))
        ->name('provinces.index');

    Route::get('/work-types', fn () => WorkType::orderBy('title')->get(['id', 'title', 'image']))
        ->name('work-types.index');

    Route::get('/categories', fn () => Category::orderBy('name')->get(['id', 'name', 'image_url']))
        ->name('categories.index');

    // The map screen's pins — one shot like the web's /map page, public with
    // the marketplace above.
    Route::get('/map', [MapController::class, 'index'])->name('map.index');

    // Browsing stays public so a signed-out visitor can look around the marketplace.
    Route::prefix('works')->name('works.')->group(function () {
        Route::get('/', [WorkController::class, 'index'])->name('index');
        Route::get('/{work}/reviews', [ReviewController::class, 'workReviews'])->name('reviews.index');
        Route::get('/{work}', [WorkController::class, 'show'])->name('show');
    });

    Route::prefix('recruits')->name('recruits.')->group(function () {
        Route::get('/', [RecruitController::class, 'index'])->name('index');
        Route::get('/{recruit}/reviews', [ReviewController::class, 'recruitReviews'])->name('reviews.index');
        Route::get('/{recruit}', [RecruitController::class, 'show'])->name('show');
    });

    // A provider's portfolio detail is public — a customer may look at a
    // finished work from anywhere, like on the web.
    Route::get('/portfolios/{portfolio}', [PortfolioController::class, 'show'])->name('portfolios.show');

    Route::middleware('auth:sanctum')->group(function () {
        // The portfolio management half sits behind the token — only the owner
        // lists, adds, edits, or removes their finished works.
        Route::prefix('portfolios')->name('portfolios.')->group(function () {
            Route::get('/', [PortfolioController::class, 'index'])->name('index');
            Route::post('/', [PortfolioController::class, 'store'])->name('store');
            Route::put('/{portfolio}', [PortfolioController::class, 'update'])->name('update');
            Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])->name('destroy');
        });
        // The caller's own wallet — a read of its balance and ledger. Money
        // only moves through the flows that earn or spend it (promotions,
        // refunds, admin adjustments), so this API has no write endpoint.
        Route::prefix('wallet')->name('wallet.')->group(function () {
            Route::get('/', [WalletController::class, 'show'])->name('show');
            Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        });
        // Push tokens register the caller's own device — the token comes from
        // the app, the user comes from the token itself.
        Route::prefix('push-tokens')->name('push-tokens.')->group(function () {
            Route::post('/', [PushTokenController::class, 'store'])->name('store');
        });
        Route::prefix('uploads')->name('uploads.')->group(function () {
            Route::post('/image', [UploadController::class, 'image'])->name('image');
            Route::post('/avatar', [UploadController::class, 'avatar'])->name('avatar');
        });

        // Bookmarks the client saves from the marketplace. Everything is scoped
        // to the caller's token — listing ids come from the body, favorite ids
        // from the path, so a guessed id matches nothing.
        Route::prefix('favorites')->name('favorites.')->group(function () {
            Route::get('/', [FavoriteController::class, 'index'])->name('index');
            Route::get('/check', [FavoriteController::class, 'check'])->name('check');
            Route::post('/', [FavoriteController::class, 'store'])->name('store');
            Route::delete('/{favorite}', [FavoriteController::class, 'destroy'])->name('destroy');
        });

        // What the system told this user about — the booking endpoints already
        // write these, they just had nowhere for the mobile client to read them.
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
            Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
            Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('read');
        });

        // Direct messages. Every route resolves the caller against the channel's
        // participants before reading or writing — the channel id comes from the
        // client and ids are small sequential integers.
        Route::prefix('messenger')->name('messenger.')->group(function () {
            Route::get('/channels', [MessengerController::class, 'index'])->name('channels.index');
            Route::get('/unread', [MessengerController::class, 'unread'])->name('unread');
            Route::post(
                '/work-bookings/{workBooking}/channel',
                [MessengerController::class, 'openForWorkBooking'],
            )->name('work-bookings.channel');
            Route::post(
                '/recruit-bookings/{recruitBooking}/channel',
                [MessengerController::class, 'openForRecruitBooking'],
            )->name('recruit-bookings.channel');
            Route::get('/channels/{channel}', [MessengerController::class, 'show'])->name('channels.show');
            Route::get('/channels/{channel}/messages', [MessengerController::class, 'messages'])->name('channels.messages');
            Route::post('/channels/{channel}/messages', [MessengerController::class, 'storeMessage'])->name('channels.messages.store');
            Route::post('/channels/{channel}/seen', [MessengerController::class, 'markSeen'])->name('channels.seen');
        });

        Route::prefix('works')->name('works.')->group(function () {
            Route::post('/', [WorkController::class, 'store'])->name('store');

            // The weekly availability schedule plus one-off blocked dates —
            // owner management only, mirroring the web form at
            // /works/{id}/availability.
            Route::prefix('{work}/availability')->name('availability.')->group(function () {
                Route::get('/', [AvailabilityController::class, 'show'])->name('show');
                Route::put('/', [AvailabilityController::class, 'update'])->name('update');
                Route::post('/blocked-dates', [AvailabilityController::class, 'storeBlockedDate'])->name('blocked-dates.store');
                Route::delete('/blocked-dates/{blockedDate}', [AvailabilityController::class, 'destroyBlockedDate'])->name('blocked-dates.destroy');
            });

            Route::post('/{work}/bookings', [WorkBookingController::class, 'store'])->name('bookings.store');
            Route::post('/{work}/reviews', [ReviewController::class, 'storeWorkReview'])->name('reviews.store');
        });

        Route::prefix('recruits')->name('recruits.')->group(function () {
            Route::post('/', [RecruitController::class, 'store'])->name('store');
            Route::post('/{recruit}/bookings', [RecruitBookingController::class, 'store'])->name('bookings.store');
            Route::post('/{recruit}/reviews', [ReviewController::class, 'storeRecruitReview'])->name('reviews.store');
        });

        // Answers a review has drawn — the notification walks the thread back to
        // the listing underneath, so the recipient can be routed there.
        Route::post('/reviews/{post}/replies', [ReviewController::class, 'storeReply'])->name('reviews.reply');

        // Live work sessions for confirmed bookings — the worker starts from a
        // booking the path names, and only the two participants touch it after.
        Route::prefix('sessions')->name('sessions.')->group(function () {
            Route::get('/', [SessionController::class, 'index'])->name('index');
            Route::post('/work-bookings/{workBooking}/start', [SessionController::class, 'start'])->name('start');
            Route::get('/{session}', [SessionController::class, 'show'])->name('show');
            Route::post('/{session}/pause', [SessionController::class, 'pause'])->name('pause');
            Route::post('/{session}/resume', [SessionController::class, 'resume'])->name('resume');
            Route::post('/{session}/stop', [SessionController::class, 'stop'])->name('stop');
            Route::post('/{session}/confirm', [SessionController::class, 'confirm'])->name('confirm');
            Route::post('/{session}/locations', [SessionController::class, 'logLocation'])->name('locations');
        });

        Route::prefix('work-bookings')->name('work-bookings.')->group(function () {
            Route::get('/', [WorkBookingController::class, 'indexAsCustomer'])->name('index');
            Route::get('/as-owner', [WorkBookingController::class, 'indexAsOwner'])->name('index.as-owner');
            Route::get('/{workBooking}', [WorkBookingController::class, 'show'])->name('show');
            Route::post('/{workBooking}/confirm', [WorkBookingController::class, 'confirmByWorker'])->name('confirm');
            Route::post('/{workBooking}/confirm-as-customer', [WorkBookingController::class, 'confirmByCustomer'])->name('confirm.as-customer');
            Route::post('/{workBooking}/cancel', [WorkBookingController::class, 'cancel'])->name('cancel');
        });

        Route::prefix('recruit-bookings')->name('recruit-bookings.')->group(function () {
            Route::get('/', [RecruitBookingController::class, 'indexAsApplicant'])->name('index');
            Route::get('/as-employer', [RecruitBookingController::class, 'indexAsEmployer'])->name('index.as-employer');
            Route::get('/{recruitBooking}', [RecruitBookingController::class, 'show'])->name('show');
            Route::post('/{recruitBooking}/confirm', [RecruitBookingController::class, 'confirmByEmployer'])->name('confirm');
            Route::post('/{recruitBooking}/confirm-as-applicant', [RecruitBookingController::class, 'confirmByApplicant'])->name('confirm.as-applicant');
            Route::post('/{recruitBooking}/cancel', [RecruitBookingController::class, 'cancel'])->name('cancel');
        });
    });
});
