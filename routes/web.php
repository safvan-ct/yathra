<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trip/{id}/{from_stop_id}/{to_stop_id}', [HomeController::class, 'showTrip'])->name('trip.show');

require __DIR__ . '/admin.php';
