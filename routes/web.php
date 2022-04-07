<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', DashboardController::class);

Route::get('/products/get_product/{id_product}', [ProductController::class, 'get_product']);

Route::resources([
    '/products' => ProductController::class,
    '/users' => UserController::class,
    '/purchases' => PurchaseController::class,
]);
