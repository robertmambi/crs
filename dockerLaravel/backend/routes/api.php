<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

	// Public
	Route::post('/auth/login', [AuthController::class, 'login']);

	// Protected
	Route::middleware('auth:sanctum')->group(function () {
		Route::get('/auth/me', [AuthController::class, 'me']);
		Route::post('/auth/logout', [AuthController::class, 'logout']);
	});
