<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouncilController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SignupController;
use App\Http\Middleware\AuthenticateToken;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/registrations', [ApplicantController::class, 'store']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/signup', [SignupController::class, 'store']);
Route::get('/councils', [CouncilController::class, 'index']);

// Verify token (can be called with header or query)
Route::get('/auth/verify', [AuthController::class, 'verify']);
Route::delete('/auth/logout', [AuthController::class, 'logout']);

// Protected
Route::middleware([AuthenticateToken::class])->group(function () {
    Route::get('/registrations', [ApplicantController::class, 'index']);
    Route::patch('/registrations/{id}', [ApplicantController::class, 'update']);
    Route::get('/logs', [LogController::class, 'handle']);
});
