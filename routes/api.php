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
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);
Route::post('participants', [ParticipantController::class, 'store']);
Route::get('/events/{eventId}/participants/count', [ParticipantController::class, 'getParticipantCount']);

Route::middleware(['auth:api'])->group(function () {
    
    Route::get('logout', [UserController::class, 'logout']);   
    
    Route::middleware(['superadmin'])->group(function () {

        Route::get('users', [UserController::class, 'index']);
        Route::post('users/{user}', [UserController::class, 'show']);

        Route::post('events', [EventController::class, 'store']);
        Route::put('events/{event}', [EventController::class, 'update']);
        Route::delete('events/{event}', [EventController::class, 'destroy']);

        Route::get('participants', [ParticipantController::class, 'index']);
        Route::get('participants/{participant}', [ParticipantController::class, 'show']);
        Route::put('participants/{participant}', [ParticipantController::class, 'update']);
        Route::delete('participants', [ParticipantController::class, 'destroy']);
    });   
    
    
});
