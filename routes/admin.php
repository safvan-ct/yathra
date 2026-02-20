<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Bus\BusController;
use App\Http\Controllers\Admin\Bus\OperatorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Route\RouteDirectionController;
use App\Http\Controllers\Admin\Route\RouteDirectionStopController;
use App\Http\Controllers\Admin\Route\RoutePatternController;
use App\Http\Controllers\Admin\Stop\CityController;
use App\Http\Controllers\Admin\Stop\DistrictController;
use App\Http\Controllers\Admin\Stop\StopController;
use App\Http\Controllers\Admin\Trip\TripScheduleController;
use Illuminate\Support\Facades\Route;

// Route::get('login', [LoginController::class, 'create'])->name('login');

Route::middleware('guest:web')->prefix('backend')->name('backend.')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
});

Route::get('/backend/dashboard', [DashboardController::class, 'index'])->middleware(['auth:web', 'verified'])->name('backend.dashboard');

Route::get('stops', [StopController::class, 'search']);

Route::middleware(['auth:web'])->group(function () {
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
    Route::get('route-directions', [RouteDirectionController::class, 'search'])->name('route-directions.search');
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

    // Operators
    Route::get('bus-operators', [OperatorController::class, 'search']);
    Route::prefix('bus-operator')->name('bus-operator.')->group(function () {
        Route::get('/form/{id}', [OperatorController::class, 'form'])->name('form');
        Route::get('/datatable', [OperatorController::class, 'dataTable'])->name('datatable');
        Route::patch('/toggle-status/{operator}', [OperatorController::class, 'toggleStatus'])->name('toggle-status');

        Route::post('/import/preview', [OperatorController::class, 'importPreview'])->name('import.preview');
        Route::post('/import/{id}/confirm', [OperatorController::class, 'importConfirm'])->name('import.confirm');
    });
    Route::resource('bus-operator', OperatorController::class)->only(['index', 'store', 'update']);

    // Bus
    Route::get('buses', [BusController::class, 'search'])->name('buses.search');
    Route::prefix('bus')->name('bus.')->group(function () {
        Route::get('/form/{id}', [BusController::class, 'form'])->name('form');
        Route::get('/datatable', [BusController::class, 'dataTable'])->name('datatable');
        Route::patch('/toggle-status/{bus}', [BusController::class, 'toggleStatus'])->name('toggle-status');

        Route::post('/import/preview', [BusController::class, 'importPreview'])->name('import.preview');
        Route::post('/import/{id}/confirm', [BusController::class, 'importConfirm'])->name('import.confirm');
    });
    Route::resource('bus', BusController::class)->only(['index', 'store', 'update'])->parameters(['bus' => 'bus']);

    // Trip Schedule
    Route::get('trip-schedules', [TripScheduleController::class, 'search']);
    Route::prefix('trip-schedule')->name('trip-schedule.')->group(function () {
        Route::get('/form/{id}', [TripScheduleController::class, 'form'])->name('form');
        Route::get('/datatable', [TripScheduleController::class, 'dataTable'])->name('datatable');
        Route::patch('/toggle-status/{tripSchedule}', [TripScheduleController::class, 'toggleStatus'])->name('toggle-status');

        Route::post('/import/confirm', [TripScheduleController::class, 'importConfirm'])->name('import.confirm');
    });
    Route::resource('trip-schedule', TripScheduleController::class)->only(['index', 'store', 'update']);
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
});
