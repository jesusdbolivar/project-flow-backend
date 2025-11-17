<?php

use App\Http\Controllers\ProjectAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('schema', \App\Http\Controllers\SchemaAPIController::class)
    ->only(['show', 'update']);

Route::apiResource('projects', ProjectAPIController::class);

