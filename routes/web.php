<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MealLogController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeDetailController;

Route::get('/', function () {
    if (session('firebase_uid')) {
        return redirect('/home'); // already logged in
    }
    return redirect('/login'); // not logged in
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/auth/google', [AuthController::class, 'googleLogin']);

Route::middleware('firebase.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/meal-log', [MealLogController::class, 'index']);
    Route::get('/favorites', [FavoritesController::class, 'index']);
    Route::get('/profile', function () { return view('pages.profile'); });

    Route::post('/chat', [ChatController::class, 'send']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/meal-log/store', [MealLogController::class, 'store']);
    Route::post('/meal-log/update', [MealLogController::class, 'update']);
    Route::post('/meal-log/delete', [MealLogController::class, 'destroy']);
    Route::post('/favorites/add', [FavoritesController::class, 'store']);
    Route::post('/favorites/remove', [FavoritesController::class, 'destroy']);

    Route::get('/home', [RecipeController::class, 'index'])->name('home');
    Route::get('/recipe/{id}', [RecipeDetailController::class, 'show'])->name('recipe.show');
});