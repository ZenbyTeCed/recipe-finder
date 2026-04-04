<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/auth/google', [AuthController::class, 'googleLogin']);

Route::middleware('firebase.auth')->group(function () {
    Route::get('/home', function () { return view('pages.home'); });
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/meal-log', function () { return view('pages.meal-log'); });
    Route::get('/favorites', function () { return view('pages.favorites'); });
    Route::get('/profile', function () { return view('pages.profile'); });
    Route::get('/recipe', function () { return view('pages.recipe'); });

    Route::post('/chat', [ChatController::class, 'send']);

    Route::post('/profile/update', [ProfileController::class, 'update']);
});