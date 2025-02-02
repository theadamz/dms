<?php

use Illuminate\Support\Facades\Route;

// category
Route::prefix("categories")->middleware(['access:category'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Basic\CategoryController::class, 'index'])->can('category');
    Route::post('/', [\App\Http\Controllers\Basic\CategoryController::class, 'store'])->can('category-create');
    Route::get('/{id}', [\App\Http\Controllers\Basic\CategoryController::class, 'show'])->can('category-read');
    Route::put('/{id}', [\App\Http\Controllers\Basic\CategoryController::class, 'update'])->can('category-edit');
    Route::delete('/', [\App\Http\Controllers\Basic\CategoryController::class, 'destroy'])->can('category-delete');
    Route::post('/imports', [\App\Http\Controllers\Basic\CategoryController::class, 'storeImport'])->can('category-import');
    Route::post('/exports', [\App\Http\Controllers\Basic\CategoryController::class, 'export'])->can('category-export');
});

// category sub
Route::prefix("category-subs")->middleware(['access:category-sub'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Basic\CategorySubController::class, 'index'])->can('category-sub');
    Route::post('/', [\App\Http\Controllers\Basic\CategorySubController::class, 'store'])->can('category-sub-create');
    Route::get('/{id}', [\App\Http\Controllers\Basic\CategorySubController::class, 'show'])->can('category-sub-read');
    Route::put('/{id}', [\App\Http\Controllers\Basic\CategorySubController::class, 'update'])->can('category-sub-edit');
    Route::delete('/', [\App\Http\Controllers\Basic\CategorySubController::class, 'destroy'])->can('category-sub-delete');
    Route::post('/imports', [\App\Http\Controllers\Basic\CategorySubController::class, 'storeImport'])->can('category-sub-import');
    Route::post('/exports', [\App\Http\Controllers\Basic\CategorySubController::class, 'export'])->can('category-sub-export');
});

// approval set
Route::prefix("approval-sets")->middleware(['access:approval-set'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'index'])->can('approval-set');
    Route::post('/', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'store'])->can('approval-set-create');
    Route::get('/{id}', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'show'])->can('approval-set-read');
    Route::put('/{id}', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'update'])->can('approval-set-edit');
    Route::delete('/', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'destroy'])->can('approval-set-delete');
    Route::post('/exports', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'export'])->can('approval-set-export');
});
