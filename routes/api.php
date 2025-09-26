<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\WilayaController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PassengerController;
use App\Http\Controllers\Api\SeatPriceController;
use App\Http\Controllers\Api\VehicleModelController;

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

    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

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

    // Temp Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        Route::post('/driver/update-status', [DashboardController::class, 'updateDriverStatus']);
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

    // Brand routes
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
    });

    // Vehicle Model routes
    Route::prefix('models')->group(function () {
        Route::get('/', [VehicleModelController::class, 'index']);
    });

    // Color routes
    Route::prefix('colors')->group(function () {
        Route::get('/', [ColorController::class, 'index']);
    });


    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Passenger routes
        Route::prefix('passengers')->group(function () {
            Route::patch('/', [PassengerController::class, 'update']);
        });

        // Driver routes
        Route::prefix('drivers')->group(function () {
            Route::post('/', [DriverController::class, 'store']);
            Route::patch('/', [DriverController::class, 'update']);
        });

        Route::prefix('trips')->group(function () {
            Route::post('/{type}', [TripController::class, 'store']);
            Route::get('/{type}', [TripController::class, 'index']);
        });
    });

});


