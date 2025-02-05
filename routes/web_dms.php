<?php

use Illuminate\Support\Facades\Route;

// my document
Route::post('/', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'store'])->middleware(['access:my-doc-new'])->can('doc-create')->name('documents.store');
Route::get('/new', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'create'])->middleware(['access:my-doc-new'])->can('doc-create')->name('documents.create');
Route::get('/list', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'index'])->middleware(['access:my-doc-list'])->can('my-doc-list-access')->name('documents.list');
