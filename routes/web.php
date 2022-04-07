<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', DashboardController::class);

Route::resources([
    '/products' => ProductController::class,
    '/users' => UserController::class
]);
