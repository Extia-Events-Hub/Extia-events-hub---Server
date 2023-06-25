<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V2\EventController;
use App\Http\Controllers\Api\V2\ParticipantController;
use App\Mail\EventTicket;
use App\Models\Participant;
use Illuminate\Support\Facades\Mail;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('participants', [ParticipantController::class, 'store']);
Route::get('events', [EventController::class, 'index']);
Route::get('events', [EventController::class, 'show']);

Route::middleware(['auth:api'])->group(function () {
    
    Route::get('logout', [UserController::class, 'logout']);   
    
    Route::middleware(['superadmin'])->group(function () {
        Route::apiResource('events', EventController::class);
        Route::apiResource('participants', ParticipantController::class);
        Route::apiResource('users', UserController::class);        
    });   
    
});
