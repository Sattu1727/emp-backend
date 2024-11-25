<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/getemployees', [EmployeeController::class, 'index']);

// Admin login and register
Route::post('/admin/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'login']);


Route::post('/generate-token', [TokenController::class, 'generateToken']);
Route::get('/get-token', [TokenController::class, 'getToken']);