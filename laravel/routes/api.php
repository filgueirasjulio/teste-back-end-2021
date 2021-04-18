<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MeController;

Route::prefix('v1')->group(function(){
    //Auth routes
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

    //Me routes
   Route::prefix('me')->group(function() {
     Route::get('/', [MeController::class, 'index']);
     Route::put('/update', [MeController::class, 'update']);
   });
});

