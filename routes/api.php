<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\ConsultationController;
use App\Http\Controllers\Api\V1\SpotController;
use App\Http\Controllers\API\v1\VaccinationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/test', function () {
    return response()->json([
        'message' => 'POST jalan'
    ]);
});

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    //Consultation
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::post('/consultations', [ConsultationController::class, 'store']);

    //spot
    Route::get('/spots', [SpotController::class, 'index']);
    Route::get('/spots/{id}', [SpotController::class, 'show']);

    //vaccanition
    Route::post('/vaccinations', [VaccinationController::class, 'store']);
    Route::get('/vaccinations', [VaccinationController::class, 'index']);
});
