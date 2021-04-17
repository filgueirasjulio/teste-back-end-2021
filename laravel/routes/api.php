<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::prefix('v1')->group(function(){
    Route::post('auth/login', [AuthController::class, 'login']);
});
