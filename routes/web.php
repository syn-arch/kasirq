<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class);

    Route::get('/products/get_product/{product}', [ProductController::class, 'get_product']);
    Route::get('/purchases/print/{purchase}', [PurchaseController::class, 'print']);

    Route::resources([
        '/products' => ProductController::class,
        '/users' => UserController::class,
        '/purchases' => PurchaseController::class,
    ]);

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/print/{start}/{end}', [ReportController::class, 'print']);
});
