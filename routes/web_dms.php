<?php

use Illuminate\Support\Facades\Route;

// my document
Route::get('/', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'index'])->middleware(['access:my-doc-list'])->can('my-doc-list')->name('documents.list');
Route::post('/', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'store'])->middleware(['access:my-doc-new'])->can('my-doc-create')->name('documents.store');
Route::get('/create', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'create'])->middleware(['access:my-doc-new'])->can('my-doc-create')->name('documents.create');
