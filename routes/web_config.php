<?php

use Illuminate\Support\Facades\Route;

// config > department
Route::middleware(['can:config-department', 'access:config-department'])->group(function () {
    Route::get('/departments', [\App\Http\Controllers\Config\DepartmentController::class, 'index'])->can('config-department');
    Route::post('/departments', [\App\Http\Controllers\Config\DepartmentController::class, 'store'])->can('config-department-create');
    Route::get('/departments/{id}', [\App\Http\Controllers\Config\DepartmentController::class, 'show'])->can('config-department-read');
    Route::put('/departments/{id}', [\App\Http\Controllers\Config\DepartmentController::class, 'update'])->can('config-department-edit');
    Route::delete('/departments', [\App\Http\Controllers\Config\DepartmentController::class, 'destroy'])->can('config-department-delete');
    Route::post('/departments/imports', [\App\Http\Controllers\Config\DepartmentController::class, 'storeImport'])->can('config-department-import');
    Route::post('/departments/exports', [\App\Http\Controllers\Config\DepartmentController::class, 'export'])->can('config-department-export');
});

// config > role
Route::middleware(['can:config-role', 'access:config-role'])->group(function () {
    Route::get('/roles', [\App\Http\Controllers\Config\RoleController::class, 'index'])->can('config-role');
    Route::post('/roles', [\App\Http\Controllers\Config\RoleController::class, 'store'])->can('config-role-create');
    Route::get('/roles/{id}', [\App\Http\Controllers\Config\RoleController::class, 'show'])->can('config-role-read');
    Route::put('/roles/{id}', [\App\Http\Controllers\Config\RoleController::class, 'update'])->can('config-role-edit');
    Route::delete('/roles', [\App\Http\Controllers\Config\RoleController::class, 'destroy'])->can('config-role-delete');
});

// config > access
Route::middleware(['can:config-role-access', 'access:config-role-access'])->group(function () {
    Route::get('/accesses', [\App\Http\Controllers\Config\AccessController::class, 'index'])->can('config-role-access');
    Route::get('/accesses/{roleId}', [\App\Http\Controllers\Config\AccessController::class, 'retriveRoleAccesses'])->can('config-role-access-read');
    Route::get('/accesses/{roleId}/{accessCode}', [\App\Http\Controllers\Config\AccessController::class, 'show'])->can('config-role-access-read');
    Route::put('/accesses/{roleId}', [\App\Http\Controllers\Config\AccessController::class, 'update'])->can('config-role-access-edit');
    Route::post('/accesses', [\App\Http\Controllers\Config\AccessController::class, 'store'])->can('config-role-access-create');
    Route::delete('/accesses', [\App\Http\Controllers\Config\AccessController::class, 'destroy'])->can('config-role-access-delete');
    Route::post('/accesses/duplicate', [\App\Http\Controllers\Config\AccessController::class, 'duplicate'])->can('config-role-access-create');
});

// config > user
Route::middleware(['can:config-user', 'access:config-user'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\Config\UserController::class, 'index'])->can('config-user');
    Route::post('/users', [\App\Http\Controllers\Config\UserController::class, 'store'])->can('config-user-create');
    Route::get('/users/{id}', [\App\Http\Controllers\Config\UserController::class, 'show'])->can('config-user-read');
    Route::put('/users/{id}', [\App\Http\Controllers\Config\UserController::class, 'update'])->can('config-user-edit');
    Route::delete('/users', [\App\Http\Controllers\Config\UserController::class, 'destroy'])->can('config-user-delete');
});

// config > user access
Route::middleware(['can:config-user-access', 'access:config-user-access'])->group(function () {
    Route::get('/user-accesses', [\App\Http\Controllers\Config\UserAccessController::class, 'index'])->can('config-user-access');
    Route::get('/user-accesses/{userId}', [\App\Http\Controllers\Config\UserAccessController::class, 'retriveUserAccesses'])->can('config-user-access-read');
    Route::get('/user-accesses/{userId}/{accessCode}', [\App\Http\Controllers\Config\UserAccessController::class, 'show'])->can('config-user-access-read');
    Route::put('/user-accesses/{userId}', [\App\Http\Controllers\Config\UserAccessController::class, 'update'])->can('config-user-access-edit');
    Route::post('/user-accesses', [\App\Http\Controllers\Config\UserAccessController::class, 'store'])->can('config-user-access-create');
    Route::delete('/user-accesses', [\App\Http\Controllers\Config\UserAccessController::class, 'destroy'])->can('config-user-access-delete');
    Route::post('/user-accesses/duplicate', [\App\Http\Controllers\Config\UserAccessController::class, 'duplicate'])->can('config-user-access-create');
});
