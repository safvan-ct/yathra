<?php

use App\Http\Controllers\Operator\BusController;
use App\Http\Controllers\Operator\LoginController;
use App\Http\Controllers\Operator\TripController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'create'])->name('login');

Route::middleware('guest:operator')->prefix('operator')->name('operator.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth:operator')->prefix('operator')->name('operator.')->group(function () {
    Route::get('dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('bus', [BusController::class, 'index'])->name('bus.index');
    Route::post('bus/store', [BusController::class, 'store'])->name('bus.store');
    Route::put('bus/{bus}/update', [BusController::class, 'update'])->name('bus.update');

    Route::get('trip', [TripController::class, 'index'])->name('trip.index');
    Route::post('trip/store', [TripController::class, 'store'])->name('trip.store');
    Route::put('trip/{trip}/update', [TripController::class, 'update'])->name('trip.update');
});
