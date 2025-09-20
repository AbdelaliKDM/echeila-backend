<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WilayaController;
use App\Http\Controllers\Api\SeatPriceController;

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

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return response()->json([
            'status' => 1,
            'message' => trans('http-statuses.200'),
            'data' => new \App\Http\Resources\UserResource($request->user())
        ]);
    });

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/check-phone', [AuthController::class, 'checkPhone']);
        Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/logout', [AuthController::class, 'logout']);
            Route::post('/reset-password', [AuthController::class, 'resetPassword']);
            Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
        });
    });

    // Wilaya routes
    Route::prefix('wilayas')->group(function () {
        Route::get('/', [WilayaController::class, 'index']);
    });

    // Seat Price routes
    Route::prefix('seat-prices')->group(function () {
        Route::get('/', [SeatPriceController::class, 'index']);
        Route::get('/{startingWilayaId}/{arrivalWilayaId}', [SeatPriceController::class, 'show']);
    });

});


