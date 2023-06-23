<?php

use App\Http\Controllers\Api\V1\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);
Route::resource('cities', CityController::class);

Route::middleware(['auth:api'])->group(function () {

    Route::get('/auth/logout', [UserController::class, 'logout']);

    // Route::middleware(['superadmin'])->group(function () {

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    // });   

    
});
