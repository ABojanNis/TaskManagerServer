<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register',  [AuthController::class, 'register']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'tasks'
], function () {
    Route::get('/', [TasksController::class, 'index']);
    Route::post('/', [TasksController::class, 'create']);
    Route::get('/{task}/completed', [TasksController::class, 'markAsCompleted']);
    Route::get('/{task}/failed', [TasksController::class, 'markAsFailed']);
    Route::get('/most-successfully', [TasksController::class, 'getMostSuccessfullyUsers']);
});
