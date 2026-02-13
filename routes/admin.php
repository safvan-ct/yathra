<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Route\RoutePatternController;
use App\Http\Controllers\Admin\Route\RoutePatternStopController;
use App\Http\Controllers\Admin\Route\StopController;
use App\Http\Controllers\Admin\Stop\DistrictController;
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

Route::prefix('district')->name('district.')->group(function () {
    Route::get('/form/{id}', [DistrictController::class, 'form'])->name('form');
    Route::get('/datatable', [DistrictController::class, 'dataTable'])->name('datatable');
    Route::patch('/toggle-status/{district}', [DistrictController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/import/{state}/preview', [DistrictController::class, 'importPreview'])->name('import.preview');
    Route::post('/import/{state}/confirm', [DistrictController::class, 'importConfirm'])->name('import.confirm');
});
Route::resource('district', DistrictController::class)->only(['index', 'store', 'update']);

Route::prefix('backend')->name('backend.')->middleware(['auth'])->group(function () {
    Route::get('stops', [DashboardController::class, 'stops']);
    Route::get('route-stops/{id}', [DashboardController::class, 'getPatternStops'])->name('route-stops');

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [ProfileController::class, 'password'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

    Route::prefix('stop')
        ->name('stop.')
        ->controller(StopController::class)
        ->group(function () {
            Route::post('/import/confirm', 'importConfirm')->name('import.confirm');
            Route::post('/import/preview', 'importPreview')->name('import.preview');

            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{stop}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id?}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('route-pattern')
        ->name('route-pattern.')
        ->controller(RoutePatternController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{routePattern}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            Route::get('/form/{id?}', 'form')->name('form');
            Route::get('/datatable', 'dataTable')->name('datatable');
            Route::patch('/{id}/toggle-status', 'toggleStatus')->name('toggle-status');
        });

    Route::prefix('route-pattern-stop')
        ->name('route-pattern-stop.')
        ->controller(RoutePatternStopController::class)
        ->group(function () {
            Route::get('/{routePattern}', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });
});
