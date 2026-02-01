<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DummyTableController;
use App\Http\Controllers\InventoryBatchController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API auth routes (so frontend can call /api/login, /api/register, /api/logout)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('api.login');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('api.register');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('api.logout');

// Inventory resources
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('inventory-batches', InventoryBatchController::class);
    Route::apiResource('inventory-movements', InventoryMovementController::class)->only(['index', 'show', 'store']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('dummy-tables', DummyTableController::class);
});

