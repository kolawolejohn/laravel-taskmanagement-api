<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/health', HealthController::class);
Route::apiResource('tasks', TaskController::class);
