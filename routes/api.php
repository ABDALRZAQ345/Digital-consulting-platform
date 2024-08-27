<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FreeTimeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('/update', [AuthController::class, 'update'])->name('update');
    Route::delete('/delete_account', [AuthController::class, 'delete'])->name('delete-account');
});

/// FreeTime Routes
Route::group([], function () {
    Route::apiResource('user.free_times', FreeTimeController::class)->middleware('auth:sanctum')->only(['index', 'show']);
    Route::apiResource('free_times', FreeTimeController::class)->middleware(['auth:sanctum', 'IsExpert'])->except(['index', 'show']);
    Route::post('/user/{user}/free_time/{free_time}/book', [FreeTimeController::class, 'book'])->middleware('auth:sanctum')->name('book.free_time');
});
/// Consultation Routes
Route::apiResource('/consultations', ConsultationController::class)->middleware('auth:sanctum');
/// Search Route
Route::get('/search', [SearchController::class, 'search'])->name('search')->middleware('auth:sanctum');
/// expert
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/booked', [FreeTimeController::class, 'booked'])->name('booked')->middleware('IsExpert');
    Route::get('/unbooked', [FreeTimeController::class, 'unbooked'])->name('unbooked')->middleware('IsExpert');

});
/// conversations
Route::middleware('auth:sanctum')->group(function () {
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('conversation', [ConversationController::class, 'index'])->name('conversation.index');
    Route::get('conversation/{conversation}', [ConversationController::class, 'show'])->name('conversation.show');
});
/// admin routes
Route::middleware(['auth:sanctum', 'IsAdmin'])->group(function () {
    Route::post('/admin/user/{user}/change_role', [AdminController::class, 'Change_role'])->name('admin.changeRole');
    Route::delete('/admin/user/{user}/delete_account', [AdminController::class, 'delete_user'])->name('admin.delete');
});
///user profile
Route::prefix('users')->name('users.')->middleware('auth:sanctum')->group(function () {
    Route::get('{user}', [UserController::class, 'show'])->name('show');
    Route::post('{user}/rate', [UserController::class, 'rate'])->name('rate');
    Route::post('{user}/favorite', [FavoriteController::class, 'store'])->name('favorite');
});
/// user routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/Appointments', [FreeTimeController::class, 'Appointments'])->name('Appointments');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites,index');
});
