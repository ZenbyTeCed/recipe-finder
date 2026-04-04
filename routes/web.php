<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MealLogController;
use App\Http\Controllers\FavoritesController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/auth/google', [AuthController::class, 'googleLogin']);

Route::middleware('firebase.auth')->group(function () {
    Route::get('/home', function () { return view('pages.home'); });
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/meal-log', [MealLogController::class, 'index']);
    Route::get('/favorites', [FavoritesController::class, 'index']);
    Route::get('/profile', function () { return view('pages.profile'); });
    Route::get('/recipe', function () { return view('pages.recipe'); });

    Route::post('/chat', [ChatController::class, 'send']);

    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/meal-log/store', [MealLogController::class, 'store']);
    Route::post('/meal-log/delete', [MealLogController::class, 'destroy']);
    Route::post('/favorites/add', [FavoritesController::class, 'store']);
    Route::post('/favorites/remove', [FavoritesController::class, 'destroy']);
});