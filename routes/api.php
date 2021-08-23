<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// login route
Route::post('/login', [AuthController::class, 'login']);
// register route
Route::post('/register', [AuthController::class, 'register']);
// forgot password route
Route::post('/forgotpassword', [ForgotController::class, 'forgotPassword']);
// reset password route
Route::post('/resetpassword', [ForgotController::class, 'resetPassword']);
// user info route
Route::get('/user', [UserController::class, 'user'])->middleware('auth:api');
