<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Models\Scannedproduct;

Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::get('/auth/logout', [UserController::class, 'logout']);
    Route::put('/users/{user}', [UserController::class, 'update']);    

    
    Route::prefix('superadmin')->middleware(['superadmin'])->group(function () {
        Route::resource('/users', UserController::class);
        Route::resource('/products', ProductController::class);
        Route::resource('/scannedproducts', Scannedproduct::class);
    });
    
    
    Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{product}', [ProductController::class, 'show']);                
        Route::get('/products-all', [ProductController::class, 'all']);
        Route::get('/products-by-user', [ProductController::class, 'productsByUser']);    

    
});


