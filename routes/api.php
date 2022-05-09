<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SiteController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::get('/users', [UserController::class, 'list']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/users2', [UserController::class, 'list']);
    Route::post('/update', [UserController::class, 'update']);

    // Employee
    Route::get('/employee', [EmployeeController::class, 'findOne']);
    Route::post('/employees', [EmployeeController::class, 'getAll']);
    Route::post('/employee/save', [EmployeeController::class, 'store']);
    Route::post('/employee/update', [EmployeeController::class, 'update']);
    Route::post('/employee/delete', [EmployeeController::class, 'remove']); 

    Route::get('/select2', function()
    {
    $data = ['ajay', 'therichpost', 'angular9', 'laravel7', 'restapi'];
    return Response::json($data, 200);
    });

});

// Employee
Route::group([
    'middleware' => 'api',
    // 'prefix' => 'employee'
], function ($router) {

    Route::get('/v1/employee', [EmployeeController::class, 'findOne']);
    Route::post('/v1/employee/list', [EmployeeController::class, 'getAll']);
    Route::post('/v1/employee/save', [EmployeeController::class, 'store']);
    Route::post('/v1/employee/update', [EmployeeController::class, 'update']);
    Route::post('/v1/employee/delete', [EmployeeController::class, 'remove']);

});

// Inventory > Sites
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::get('/v1/site', [SiteController::class, 'findOne']);
    Route::post('/v1/site/list', [SiteController::class, 'getAll']);
    Route::post('/v1/site/save', [SiteController::class, 'store']);
    Route::post('/v1/site/update', [SiteController::class, 'update']);
    Route::post('/v1/site/delete', [SiteController::class, 'remove']);

});

