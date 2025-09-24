<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MainController;

Route::get('/', function () {
    return view('index');
});

Route::get('/admin', function () {
    if (env('IS_ADMIN')) {
        return app(CarController::class)->index();
    }
    abort(403);
});

Route::resource('car', CarController::class);
Route::resource('reservation', ReservationController::class);

Route::patch('car/{car}/deactivate', [CarController::class, 'deactivate'])->name('car.deactivate');


Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::post('/listCars', [MainController::class, 'listCars'])->name('car.listCars');

