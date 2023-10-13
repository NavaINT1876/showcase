<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\CheckAuth;
use Illuminate\Support\Facades\Route;

Route::post('/token', [AuthController::class, 'index']);

Route::middleware([CheckAuth::class])->group(function () {
    Route::get('/todos', [TodoController::class, 'list']);

    Route::post('/todos', [TodoController::class, 'create']);

    Route::get('/todos/{id}', [TodoController::class, 'view'])->where('id', '[0-9]+');

    Route::put('/todos/{id}', [TodoController::class, 'edit'])->where('id', '[0-9]+');

    Route::delete('/todos/{id}', [TodoController::class, 'delete'])->where('id', '[0-9]+');
});
