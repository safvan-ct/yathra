<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PosterController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Service\CenterServiceController;
use App\Http\Controllers\Admin\Service\DocumentController;
use App\Http\Controllers\Admin\Service\DocumentGroupController;
use App\Http\Controllers\Admin\Service\GovernmentCenterController;
use App\Http\Controllers\Admin\Service\MenuController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'create'])->name('login');

Route::middleware('guest')->prefix('backend')->name('backend.')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
});

Route::get('/backend/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('backend.dashboard');

Route::prefix('backend')->name('backend.')->middleware(['auth'])->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [ProfileController::class, 'password'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::prefix('menu')
        ->name('menu.')
        ->controller(MenuController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{menu}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id?}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('govt-center')
        ->name('govt-center.')
        ->controller(GovernmentCenterController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{govtCenter}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id?}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('service')
        ->name('service.')
        ->controller(CenterServiceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{centerService}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id?}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('document')
        ->name('document.')
        ->controller(DocumentController::class)
        ->group(function () {
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::get('{service}', 'index')->name('index');
            Route::post('/{service}', 'store')->name('store');
            Route::put('/{document}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id}/{service}', 'form')->name('form');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('document-group')
        ->name('document-group.')
        ->controller(DocumentGroupController::class)
        ->group(function () {
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::get('/{service}', 'index')->name('index');
            Route::post('/{service}', 'store')->name('store');
            Route::put('/{documentGroup}', 'update')->name('update');

            Route::get('/form/{id}/{service?}', 'form')->name('form');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('poster')
        ->name('poster.')
        ->controller(PosterController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{poster}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('partners')
        ->name('partners.')
        ->controller(PartnerController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{partner}', 'update')->name('update');
            Route::delete('/{partner}', 'destroy')->name('destroy');

            Route::get('/form/{id?}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{partner}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('settings')
        ->name('settings.')
        ->controller(SettingsController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });

    Route::prefix('booking')
        ->name('booking.')
        ->controller(BookingController::class)
        ->group(function () {
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::post('/status', 'updateStatus')->name('status.update');
        });
});
