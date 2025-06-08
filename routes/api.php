<?php

use App\Http\Controllers\API\ExpenseController;
use App\Http\Controllers\Api\HouseController;
use App\Http\Controllers\Api\InhabitantHistoriesController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\ResidentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('residents', ResidentController::class);
Route::get('/active-residents', [ResidentController::class, 'getActiveResidents']);
Route::apiResource('houses', HouseController::class);
Route::apiResource('inhabitant-histories', InhabitantHistoriesController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('expenses', ExpenseController::class);
Route::get('/report/monthly-summary', [ReportController::class, 'monthlySummary']);
