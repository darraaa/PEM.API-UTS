<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [\App\Http\Controllers\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\UserController::class, 'update']);
    Route::post('/users/logout', [\App\Http\Controllers\UserController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/contacts/{idContact}/addresses', [AddressController::class, 'store']);
    Route::get('/contacts/{idContact}/addresses', [AddressController::class, 'index']);
    Route::get('/contacts/{idContact}/addresses/{idAddress}', [AddressController::class, 'show']);
    Route::put('/contacts/{idContact}/addresses/{idAddress}', [AddressController::class, 'update']);
    Route::delete('/contacts/{idContact}/addresses/{idAddress}', [AddressController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
});



