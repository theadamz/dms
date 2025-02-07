<?php

use Illuminate\Support\Facades\Route;

// documnets
Route::get('/', [\App\Http\Controllers\DMS\DocumentController::class, 'index'])->middleware(['access:doc'])->can('doc-read')->name('documents');

// my document
Route::post('/', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'store'])->can('doc-create')->name('documents.store');
Route::get('/new', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'create'])->middleware(['access:my-doc-new'])->can('doc-create')->name('documents.create');
Route::get('/list', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'index'])->middleware(['access:my-doc-list'])->can('my-doc-list-access')->name('documents.list');
Route::get('/{id}/edit', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'edit'])->middleware(['access:my-doc-list'])->can('doc-edit')->name('documents.edit');
Route::put('/{id}', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'update'])->can('doc-edit')->name('documents.update');
