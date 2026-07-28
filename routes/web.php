<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\RecommendationController;

// Dashboard
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/recommend', [RecommendationController::class, 'suggest'])->name('recommend');

});

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\MoodController;
use App\Http\Controllers\Admin\RecommendationController as AdminRecommendationController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\HistoryController;

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('foods', FoodController::class);
    Route::resource('moods', MoodController::class);
    Route::resource('recommendations', AdminRecommendationController::class);
    Route::resource('feedbacks', FeedbackController::class);
    Route::resource('histories', HistoryController::class);
});