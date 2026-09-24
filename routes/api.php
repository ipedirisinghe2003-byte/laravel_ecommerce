<?php

use App\Http\Controllers\CategoryController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', AuthAdmin::class])->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'apiIndex']);
    Route::post('/', [CategoryController::class, 'apiStore']);
    Route::get('/{category}', [CategoryController::class, 'apiShow']);
    Route::match(['put', 'post'], '/{category}', [CategoryController::class, 'apiUpdate']);
    Route::delete('/{category}', [CategoryController::class, 'apiDestroy']);
});
