<?php

use App\Modules\Auth\Controllers\AuthController;
use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// único endpoint público
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::patch('/users/{user}/senha', [UserController::class, 'updatePassword']);
    Route::patch('/users/{user}/reset-senha', [UserController::class, 'resetPassword']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});