<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V2\EventController;

Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);
Route::resource('events', EventController::class);

Route::middleware(['auth:api'])->group(function () {

    Route::get('/auth/logout', [UserController::class, 'logout']);

    // Route::middleware(['superadmin'])->group(function () {

        // Route::get('/users', [UserController::class, 'index']);
        // Route::get('/users/{user}', [UserController::class, 'show']);
        // Route::delete('/users/{user}', [UserController::class, 'destroy']);
    // });   

    
});
