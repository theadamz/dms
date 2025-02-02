<?php

use Illuminate\Support\Facades\Route;

// config
Route::prefix('/configs')->group(function () {
    // core
    Route::get('/users', [\App\Http\Controllers\Config\UserController::class, 'lov']);
});

// basic
Route::prefix('/basics')->group(function () {
    // core
    Route::get('/categories', [\App\Http\Controllers\Basic\CategoryController::class, 'lov'])->name('lov.basics.categories');
    Route::get('/category-subs', [\App\Http\Controllers\Basic\CategorySubController::class, 'lov'])->name('lov.basics.category-subs');
});
