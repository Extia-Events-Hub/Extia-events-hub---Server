<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V2\EventController;
use App\Http\Controllers\Api\V2\ParticipantController;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    
    Route::get('logout', [UserController::class, 'logout']);
    Route::apiResource('events', EventController::class);
    Route::apiResource('participants', ParticipantController::class);

    Route::middleware(['superadmin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        // Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });   

    
});
