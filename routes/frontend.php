<?php

use App\Livewire\Frontend\Auth\Login as FrontendLogin;
use App\Livewire\Frontend\Auth\Register as FrontendRegister;
use App\Livewire\Frontend\Auth\ForgotPassword as FrontendForgotPassword;
use App\Livewire\Frontend\Calendar;
use App\Livewire\Frontend\CategoryBrowse;
use App\Livewire\Frontend\Favorites;
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\MapExplore;
use App\Livewire\Frontend\Messenger;
use App\Livewire\Frontend\MyBookings;
use App\Livewire\Frontend\MyPortfolios;
use App\Livewire\Frontend\MySessions;
use App\Livewire\Frontend\PortfolioCreate;
use App\Livewire\Frontend\PortfolioDetail;
use App\Livewire\Frontend\PortfolioEdit;
use App\Livewire\Frontend\ReputationProfile;
use App\Livewire\Frontend\SessionDetail;
use App\Livewire\Frontend\WorkAvailabilityManage;
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
use App\Livewire\Frontend\ProfileEdit;
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

    // ─── Auth Pages (guest only) ──────────────────────────────────────
    Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
        Route::get('/login',           FrontendLogin::class)->name('login');
        Route::get('/register',        FrontendRegister::class)->name('register');
        Route::get('/forgot-password', FrontendForgotPassword::class)->name('forgot-password');
    });

    // ─── Logout (auth only) ───────────────────────────────────────────
    Route::post('/auth/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('frontend.auth.login');
    })->middleware('auth')->name('auth.logout');

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
    Route::get('/portfolios/{id}', PortfolioDetail::class)->name('portfolios.show');

    // ─── Auth Required ─────────────────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/profile',       Profile::class)->name('profile');
        Route::get('/profile/edit',  ProfileEdit::class)->name('profile.edit');
        Route::get('/notifications', Notifications::class)->name('notifications');
        Route::get('/calendar',      Calendar::class)->name('calendar');
        Route::get('/bookings',      MyBookings::class)->name('bookings');
        Route::get('/messenger',     Messenger::class)->name('messenger');
        Route::get('/favorites',     Favorites::class)->name('favorites');
        Route::get('/sessions',      MySessions::class)->name('sessions');
        Route::get('/sessions/{id}',  SessionDetail::class)->name('sessions.show');

        // Works management
        Route::get('/works/{id}/edit',         WorkEdit::class)->name('works.edit');
        Route::get('/works/{id}/bookings',     WorkBookings::class)->name('works.bookings');
        Route::get('/works/{id}/availability', WorkAvailabilityManage::class)->name('works.availability');

        // Recruits management
        Route::get('/recruits/{id}/edit',     RecruitEdit::class)->name('recruits.edit');
        Route::get('/recruits/{id}/bookings', RecruitBookings::class)->name('recruits.bookings');

        // Portfolios
        Route::get('/portfolios',          MyPortfolios::class)->name('portfolios');
        Route::get('/portfolios/create',   PortfolioCreate::class)->name('portfolios.create');
        Route::get('/portfolios/{id}/edit', PortfolioEdit::class)->name('portfolios.edit');
    });
});


