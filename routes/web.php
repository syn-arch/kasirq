<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginAction']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => 'checkRole:admin_kasir'], function () {
    Route::get('/dashboard', DashboardController::class);

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/print/{start}/{end}', [ReportController::class, 'print']);

    Route::resources([
        '/products' => ProductController::class,
        '/users' => UserController::class,
        '/outlets' => OutletController::class,
        '/settings' => SettingController::class,
    ]);
});

Route::get('/purchases/get_product/{product}', [PurchaseController::class, 'get_product']);
Route::resource('purchases', PurchaseController::class);
Route::get('/purchases/print/{purchase}', [PurchaseController::class, 'print']);
