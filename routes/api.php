<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/assets', [AssetController::class, 'index']);
Route::post('/assets', [AssetController::class, 'store']);
Route::get('/assets/{asset}', [AssetController::class, 'show']);
Route::put('/assets/{asset}', [AssetController::class, 'update']);
Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);

Route::get('/loans', [LoanController::class, 'index']);
Route::post('/loans', [LoanController::class, 'store']);
Route::get('/loans/{loan}', [LoanController::class, 'show']);
Route::patch('/loans/{loan}/return', [LoanController::class, 'returnLoan']);
