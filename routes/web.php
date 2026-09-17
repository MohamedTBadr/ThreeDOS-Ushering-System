<?php

use App\Http\Controllers\Web\ApplicantWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LogWebController;
use App\Http\Controllers\Web\RegistrationWebController;
use Illuminate\Support\Facades\Route;

// Public
Route::view('/', 'landing')->name('landing');
Route::get('/home', fn() => redirect()->route('landing'));
Route::get('/register', [RegistrationWebController::class, 'form'])->name('registration.form');
Route::get('/register-form', [RegistrationWebController::class, 'form']);
Route::post('/register-form', [RegistrationWebController::class, 'store'])->name('registration.store');

Route::get('/login', [AuthWebController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');
Route::get('/signup', [AuthWebController::class, 'registerForm'])->name('signup');
Route::post('/signup', [AuthWebController::class, 'register'])->name('signup.post');
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

// Dashboard (MVC - controllers supply data to Blade, no API fetch required for initial render)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/calendar', [DashboardController::class, 'calendar'])->name('calendar');
Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
Route::get('/usher-stats', [DashboardController::class, 'usherStats'])->name('usher.stats');

// Applicants
Route::get('/applicants/{id}', [ApplicantWebController::class, 'show'])->name('applicants.show');

// Logs (restricted to Backend Development in controller)
Route::get('/logs', [LogWebController::class, 'index'])->name('logs.index');
