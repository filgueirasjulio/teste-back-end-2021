<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

Route::prefix('v1')->group(function(){
    //Auth routes
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    //Me routes
   Route::prefix('me')->group(function() {
     Route::get('/', [MeController::class, 'index']);
     Route::put('/update', [MeController::class, 'update']);
   });

   //Products routes
   Route::prefix('products')->group(function(){
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/me-products', [ProductController::class, 'userProducts']);
    Route::get('/show/product/{id}',  [ProductController::class, 'show']);
    Route::post('/create',  [ProductController::class, 'store']);
    Route::put('/edit/product/{id}',  [ProductController::class, 'update']);
    Route::delete('/delete/product/{id}',  [ProductController::class, 'destroy']);
   });
});

