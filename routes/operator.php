<?php

use App\Http\Controllers\Operator\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'create'])->name('login');

Route::middleware('guest:operator')->prefix('operator')->name('operator.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth:operator')->prefix('operator')->name('operator.')->group(function () {
    Route::get('dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
