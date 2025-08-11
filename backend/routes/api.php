<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Categories
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    
    // Products - specific routes first before resource routes
    Route::get('products/featured', [ProductController::class, 'featured']);
    Route::get('products/category/{category}', [ProductController::class, 'byCategory']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    
    // Articles - specific routes first before resource routes  
    Route::get('articles/featured', [ArticleController::class, 'featured']);
    Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Cart management
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'add']);
        Route::put('/update/{id}', [CartController::class, 'update']);
        Route::delete('/remove/{id}', [CartController::class, 'remove']);
        Route::delete('/clear', [CartController::class, 'clear']);
    });
    
    // Orders
    Route::apiResource('orders', OrderController::class);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
});

// Admin routes (require admin role)
Route::middleware(['auth:sanctum', 'admin'])->prefix('v1/admin')->group(function () {
    // Categories management
    Route::apiResource('categories', CategoryController::class)->except(['show']);
    
    // Products management
    Route::apiResource('products', ProductController::class)->except(['show']);
    
    // Articles management
    Route::apiResource('articles', ArticleController::class)->except(['show']);
    
    // Orders management
    Route::get('orders', [OrderController::class, 'adminIndex']);
    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus']);
});
