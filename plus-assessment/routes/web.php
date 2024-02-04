<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\UpdateUserController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/users', [UserController::class, 'index'])->name('users');

Route::get('/news', [NewsController::class, 'getNews'])->name('news');

Route::get('/register-user', [RegisterUserController::class, 'index'])->name('register-user');
Route::post('/register-user', [RegisterUserController::class, 'store'])->name('register-user');

Route::get('/update-user/{id}', [UpdateUserController::class, 'index'])->name('update-user');
Route::post('/update-user/{id}', [UpdateUserController::class, 'update'])->name('update-user');
Route::delete('/update-user/{user}', [UpdateUserController::class, 'deleteUser'])->name('delete-user');

Route::middleware('auth')->group(function () {
    // Define the profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__ . '/auth.php';
