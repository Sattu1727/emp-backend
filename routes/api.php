<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// employee-form
Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/getemployees', [EmployeeController::class, 'index']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::get('/employees/name/{full_name}', [EmployeeController::class, 'showname']);
Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy']);


// Admin login and register
Route::post('/admin/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/sendcode', [AuthController::class, 'requestPasswordReset']);
Route::post('/admin/reset', [AuthController::class, 'resetPassword']);

// token
Route::post('/generate-token', [TokenController::class, 'generateToken']);
Route::get('/get-token', [TokenController::class, 'getToken']);

