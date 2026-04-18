<?php

use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;
//use App\Livewire\CarList;

Route::get('/cars', function () {
    return view('cars');
});

Route::get('/login', [WebAuthController::class, 'create'])
    ->name('login');

Route::post('/login', [WebAuthController::class, 'store'])
    ->name('login.store');

Route::post('/logout', [WebAuthController::class, 'destroy'])
    ->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');