<?php

use App\Livewire\Frontend\Calendar;
use App\Livewire\Frontend\CategoryBrowse;
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\MapExplore;
use App\Livewire\Frontend\Messenger;
use App\Livewire\Frontend\MyBookings;
use App\Livewire\Frontend\ReputationProfile;
use App\Livewire\Frontend\WorkBrowse;
use App\Livewire\Frontend\WorkDetail;
use App\Livewire\Frontend\WorkEdit;
use App\Livewire\Frontend\WorkBookings;
use App\Livewire\Frontend\WorkCreate;
use App\Livewire\Frontend\RecruitBrowse;
use App\Livewire\Frontend\RecruitCreate;
use App\Livewire\Frontend\RecruitDetail;
use App\Livewire\Frontend\RecruitEdit;
use App\Livewire\Frontend\RecruitBookings;
use App\Livewire\Frontend\Search;
use App\Livewire\Frontend\Profile;
use App\Livewire\Frontend\Notifications;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes — Chaothuk Web App
|--------------------------------------------------------------------------
|
| Public: ทุกคนดูได้ ไม่ต้อง login
| Auth:   write actions + profile + notifications ต้อง login
|
*/

Route::prefix('frontend')->name('frontend.')->group(function () {

    // ─── Public Routes ─────────────────────────────────────────────────
    Route::get('/',              Home::class)->name('home');
    Route::get('/works',         WorkBrowse::class)->name('works');
    Route::get('/works/create',  WorkCreate::class)->name('works.create')->middleware('auth');
    Route::get('/works/{id}',    WorkDetail::class)->name('works.show');
    Route::get('/recruits',      RecruitBrowse::class)->name('recruits');
    Route::get('/recruits/create', RecruitCreate::class)->name('recruits.create')->middleware('auth');
    Route::get('/recruits/{id}', RecruitDetail::class)->name('recruits.show');
    Route::get('/search',        Search::class)->name('search');
    Route::get('/categories',    CategoryBrowse::class)->name('categories');
    Route::get('/reputation/{id?}', ReputationProfile::class)->name('reputation');
    Route::get('/map',           MapExplore::class)->name('map');

    // ─── Auth Required ─────────────────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/profile',       Profile::class)->name('profile');
        Route::get('/notifications', Notifications::class)->name('notifications');
        Route::get('/calendar',      Calendar::class)->name('calendar');
        Route::get('/bookings',      MyBookings::class)->name('bookings');
        Route::get('/messenger',     Messenger::class)->name('messenger');
        Route::get('/works/{id}/edit',     WorkEdit::class)->name('works.edit');
        Route::get('/works/{id}/bookings', WorkBookings::class)->name('works.bookings');
        Route::get('/recruits/{id}/edit',     RecruitEdit::class)->name('recruits.edit');
        Route::get('/recruits/{id}/bookings', RecruitBookings::class)->name('recruits.bookings');
    });
});

