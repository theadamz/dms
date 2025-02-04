<?php

use Illuminate\Support\Facades\Route;

Route::get('/sign-in-history', [\App\Http\Controllers\Account\ProfileController::class, 'datatableSignInHistory'])->name('dt.sign-in-history');

// config
Route::prefix('/configs')->group(function () {
    // core
    Route::get('/departments', [\App\Http\Controllers\Config\DepartmentController::class, 'datatable'])->name('dt.departments.roles');
    Route::get('/roles', [\App\Http\Controllers\Config\RoleController::class, 'datatable'])->name('dt.configs.roles');
    Route::get('/users', [\App\Http\Controllers\Config\UserController::class, 'datatable'])->name('dt.configs.users');
});

// basic
Route::prefix('/basics')->group(function () {
    Route::get('/categories', [\App\Http\Controllers\Basic\CategoryController::class, 'datatable'])->name('dt.basics.categories');
    Route::get('/category-subs', [\App\Http\Controllers\Basic\CategorySubController::class, 'datatable'])->name('dt.basics.category-subs');
    Route::get('/approval-sets', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'datatable'])->name('dt.basics.approval-sets');
});

// dms
Route::prefix('/documents')->group(function () {
    Route::get('/list', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'datatable'])->name('dt.documents.list');
});
