<?php

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
});

Route::group(['prefix' => 'barcode', 'as' => 'barcode.'], function () {
    Route::get('/qr/{code}', [BarcodeController::class, 'qr'])->name('qr');
    Route::get('/code128/{code}', [BarcodeController::class, 'code128'])->name('code128');
});

// Default Auth routes removed to enforce isolated frontend/backend logins
