<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Route\RouteDirectionController;
use App\Http\Controllers\Admin\Route\RouteDirectionStopController;
use App\Http\Controllers\Admin\Route\RoutePatternController;
use App\Http\Controllers\Admin\Stop\CityController;
use App\Http\Controllers\Admin\Stop\DistrictController;
use App\Http\Controllers\Admin\Stop\StopController;
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

// District
Route::get('districts', [DistrictController::class, 'search']);
Route::prefix('district')->name('district.')->group(function () {
    Route::get('/form/{id}', [DistrictController::class, 'form'])->name('form');
    Route::get('/datatable', [DistrictController::class, 'dataTable'])->name('datatable');
    Route::patch('/toggle-status/{district}', [DistrictController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/import/{state}/preview', [DistrictController::class, 'importPreview'])->name('import.preview');
    Route::post('/import/{state}/confirm', [DistrictController::class, 'importConfirm'])->name('import.confirm');
});
Route::resource('district', DistrictController::class)->only(['index', 'store', 'update']);

// Cities
Route::get('cities', [CityController::class, 'search']);
Route::prefix('city')->name('city.')->group(function () {
    Route::get('/form/{id}', [CityController::class, 'form'])->name('form');
    Route::get('/datatable', [CityController::class, 'dataTable'])->name('datatable');
    Route::patch('/toggle-status/{city}', [CityController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/import/confirm', [CityController::class, 'importConfirm'])->name('import.confirm');
});
Route::resource('city', CityController::class)->only(['index', 'store', 'update']);

// Stops
Route::get('stops', [StopController::class, 'search']);
Route::prefix('stop')->name('stop.')->group(function () {
    Route::get('/form/{id}', [StopController::class, 'form'])->name('form');
    Route::get('/datatable', [StopController::class, 'dataTable'])->name('datatable');
    Route::patch('/toggle-status/{stop}', [StopController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/import/confirm', [StopController::class, 'importConfirm'])->name('import.confirm');
});
Route::resource('stop', StopController::class)->only(['index', 'store', 'update']);

// Route Pattern
Route::get('route-patterns', [RoutePatternController::class, 'search']);
Route::prefix('route-pattern')->name('route-pattern.')->group(function () {
    Route::get('/form/{id}', [RoutePatternController::class, 'form'])->name('form');
    Route::get('/datatable', [RoutePatternController::class, 'dataTable'])->name('datatable');
    Route::patch('/toggle-status/{routePattern}', [RoutePatternController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/import/preview', [RoutePatternController::class, 'importPreview'])->name('import.preview');
    Route::post('/import/{id}/confirm', [RoutePatternController::class, 'importConfirm'])->name('import.confirm');
});
Route::resource('route-pattern', RoutePatternController::class)->only(['index', 'store', 'update']);

// Route Direction
Route::get('route-directions', [RouteDirectionController::class, 'search']);
Route::prefix('route-direction')->name('route-direction.')->group(function () {
    Route::get('/form/{id}', [RouteDirectionController::class, 'form'])->name('form');
    Route::get('/datatable', [RouteDirectionController::class, 'dataTable'])->name('datatable');
    Route::patch('/toggle-status/{routeDirection}', [RouteDirectionController::class, 'toggleStatus'])->name('toggle-status');

    Route::post('/import/preview', [RouteDirectionController::class, 'importPreview'])->name('import.preview');
    Route::post('/import/{id}/confirm', [RouteDirectionController::class, 'importConfirm'])->name('import.confirm');
});
Route::resource('route-direction', RouteDirectionController::class)->only(['index', 'store', 'update']);

// Route Direction Stops
Route::get('route-direction-stops/{id}', [RouteDirectionStopController::class, 'getStops'])->name('route-direction-stops.get');
Route::prefix('route-direction-stop')->name('route-direction-stop.')->group(function () {
    Route::post('/', [RouteDirectionStopController::class, 'store'])->name('store');
    Route::post('/import/preview', [RouteDirectionStopController::class, 'importPreview'])->name('import.preview');
    Route::post('/import/{id}/confirm', [RouteDirectionStopController::class, 'importConfirm'])->name('import.confirm');

    Route::get('/{routeDirection}', [RouteDirectionStopController::class, 'index'])->name('index');
});

Route::prefix('backend')->name('backend.')->middleware(['auth'])->group(function () {

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [ProfileController::class, 'password'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware('throttle:6,1')
    //     ->name('verification.send');

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
});
