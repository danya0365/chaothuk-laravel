<?php

use App\Http\Controllers\BarcodeController;

// Legacy Backend Routes Removed

Route::get('/', \App\Livewire\Landing::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/backend/auth/login', \App\Livewire\Backend\Auth\Login::class)->name('backend.auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/backend/auth/unauthorize', \App\Livewire\Backend\Auth\Unauthorize::class)->name('backend.auth.unauthorize');
});

Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'IsCanAccessBackend']], function () {
    Route::get('/', \App\Livewire\Backend\Dashboard::class)->name('index');
    
    // User Management
    Route::get('/users', \App\Livewire\Backend\User\Index::class)->name('users.index');
    Route::get('/users/create', \App\Livewire\Backend\User\Create::class)->name('users.create');
    Route::get('/users/{user}', \App\Livewire\Backend\User\Show::class)->name('users.show');
    Route::get('/users/{user}/edit', \App\Livewire\Backend\User\Edit::class)->name('users.edit');

    // Permissions Management
    Route::get('/permissions', \App\Livewire\Backend\Permission\Index::class)->name('permissions.index');
    Route::get('/permissions/create', \App\Livewire\Backend\Permission\Form::class)->name('permissions.create');
    Route::get('/permissions/{permission}/edit', \App\Livewire\Backend\Permission\Form::class)->name('permissions.edit');

    // Roles Management
    Route::get('/roles', \App\Livewire\Backend\Role\Index::class)->name('roles.index');
    Route::get('/roles/create', \App\Livewire\Backend\Role\Form::class)->name('roles.create');
    Route::get('/roles/{role}/edit', \App\Livewire\Backend\Role\Form::class)->name('roles.edit');
    
    // Points Management (Issues)
    Route::group(['prefix' => 'points', 'as' => 'points.'], function () {
        Route::get('/issues', \App\Livewire\Backend\Point\IssueIndex::class)->name('issues.index');
        Route::get('/issues/create', \App\Livewire\Backend\Point\IssueForm::class)->name('issues.create');
        Route::get('/issues/{issue}', \App\Livewire\Backend\Point\IssueForm::class)->name('issues.edit');
    });

    // Wallet & Payments Management
    Route::group(['prefix' => 'wallets', 'as' => 'wallets.'], function () {
        Route::get('/', \App\Livewire\Backend\Wallet\Index::class)->name('index');
        Route::get('/transactions', \App\Livewire\Backend\Wallet\Transactions::class)->name('transactions');
    });

    Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
        Route::get('/', \App\Livewire\Backend\Payment\Index::class)->name('index');
    });

    // User Verification (KYC)
    Route::group(['prefix' => 'verifications', 'as' => 'verifications.'], function () {
        Route::get('/', \App\Livewire\Backend\Verification\Index::class)->name('index');
        Route::get('/{verification}', \App\Livewire\Backend\Verification\Show::class)->name('show');
    });

    // Content Moderation (Works)
    Route::group(['prefix' => 'works', 'as' => 'works.'], function () {
        Route::get('/', \App\Livewire\Backend\Work\Index::class)->name('index');
        Route::get('/{work}', \App\Livewire\Backend\Work\Show::class)->name('show');
    });

    // Content Moderation (Recruits)
    Route::group(['prefix' => 'recruits', 'as' => 'recruits.'], function () {
        Route::get('/', \App\Livewire\Backend\Recruit\Index::class)->name('index');
        Route::get('/{recruit}', \App\Livewire\Backend\Recruit\Show::class)->name('show');
    });

    // Content Moderation (Posts & Reviews)
    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::get('/', \App\Livewire\Backend\Post\Index::class)->name('index');
        Route::get('/{post}', \App\Livewire\Backend\Post\Show::class)->name('show');
    });

    // Content Moderation (Messenger / Chats)
    Route::group(['prefix' => 'messenger', 'as' => 'messenger.'], function () {
        Route::get('/', \App\Livewire\Backend\Messenger\Index::class)->name('index');
        Route::get('/{channel}', \App\Livewire\Backend\Messenger\Show::class)->name('show');
    });

    // Dispute & Report Management
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', \App\Livewire\Backend\UserReport\Index::class)->name('index');
        Route::get('/{report}', \App\Livewire\Backend\UserReport\Show::class)->name('show');
    });

    Route::group(['prefix' => 'disputes', 'as' => 'disputes.'], function () {
        Route::get('/', \App\Livewire\Backend\Dispute\Index::class)->name('index');
        Route::get('/{dispute}', \App\Livewire\Backend\Dispute\Show::class)->name('show');
    });

    // System & Activity Logs
    Route::group(['prefix' => 'logs', 'as' => 'logs.'], function () {
        Route::get('/activity', \App\Livewire\Backend\ActivityLog\Index::class)->name('activity.index');
        Route::get('/crons', \App\Livewire\Backend\Log\CronIndex::class)->name('crons.index');
        
        Route::group(['prefix' => 'work-sessions', 'as' => 'work-sessions.'], function () {
            Route::get('/', \App\Livewire\Backend\WorkSession\Index::class)->name('index');
            Route::get('/{session}', \App\Livewire\Backend\WorkSession\Show::class)->name('show');
        });
    });

    // Notifications (Broadcast)
    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/', \App\Livewire\Backend\Notification\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Notification\Create::class)->name('create');
    });

    // Reputations & Reviews
    Route::group(['prefix' => 'reputations', 'as' => 'reputations.'], function () {
        Route::get('/', \App\Livewire\Backend\Reputation\Index::class)->name('index');
        Route::get('/reviews', \App\Livewire\Backend\Reputation\ReviewIndex::class)->name('reviews.index');
    });

    // Session Management (Kick Users)
    Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function () {
        Route::get('/', \App\Livewire\Backend\Session\Index::class)->name('index');
    });

    // Settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', \App\Livewire\Backend\Setting\Index::class)->name('index');
    });

    // Master Data & Configurations
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', \App\Livewire\Backend\Category\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Category\Form::class)->name('create');
        Route::get('/{category}/edit', \App\Livewire\Backend\Category\Form::class)->name('edit');
    });

    Route::group(['prefix' => 'work-types', 'as' => 'work-types.'], function () {
        Route::get('/', \App\Livewire\Backend\WorkType\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\WorkType\Form::class)->name('create');
        Route::get('/{workType}/edit', \App\Livewire\Backend\WorkType\Form::class)->name('edit');
    });

    Route::group(['prefix' => 'portfolios', 'as' => 'portfolios.'], function () {
        Route::get('/', \App\Livewire\Backend\Portfolio\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Portfolio\Form::class)->name('create');
        Route::get('/{portfolio}/edit', \App\Livewire\Backend\Portfolio\Form::class)->name('edit');
    });

    Route::group(['prefix' => 'banners', 'as' => 'banners.'], function () {
        Route::get('/', \App\Livewire\Backend\Banner\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Banner\Form::class)->name('create');
        Route::get('/{banner}/edit', \App\Livewire\Backend\Banner\Form::class)->name('edit');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', \App\Livewire\Backend\Setting\Index::class)->name('index');
    });

    Route::group(['prefix' => 'locations', 'as' => 'locations.'], function () {
        Route::get('/provinces', \App\Livewire\Backend\Location\ProvinceIndex::class)->name('provinces.index');
    });
});




Route::group(['prefix' => 'barcode', 'as' => 'barcode.'], function () {
    Route::get('/qr/{code}', [BarcodeController::class, 'qr'])->name('qr');
    Route::get('/code128/{code}', [BarcodeController::class, 'code128'])->name('code128');
});

// Default Auth routes removed to enforce isolated frontend/backend logins
