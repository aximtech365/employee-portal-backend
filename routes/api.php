<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        Route::apiResource('documents', DocumentController::class);
        Route::get('/documents/{document}/download', [DocumentController::class, 'download']);

        Route::get('/departments', [DepartmentController::class, 'index']);
        Route::get('/categories', [DocumentCategoryController::class, 'index']);
    });
});
