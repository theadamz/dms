<?php

use Illuminate\Support\Facades\Route;

// documnets
Route::get('/', [\App\Http\Controllers\DMS\DocumentController::class, 'index'])->middleware(['access:doc'])->can('doc-read')->name('documents');

// my document
Route::post('/', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'store'])->can('doc-create')->name('documents.store');
Route::get('/new/{refDocId?}', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'create'])->middleware(['access:my-doc-new'])->can('doc-create')->name('documents.create');
Route::get('/list', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'index'])->middleware(['access:my-doc-list'])->can('my-doc-list-access')->name('documents.list');

Route::get('/{id}', [\App\Http\Controllers\DMS\DocumentController::class, 'view'])->middleware(['access:doc'])->can('doc-read')->name('documents.view');
Route::get('/{id}/files/{documentFileId}/preview', [\App\Http\Controllers\DMS\DocumentController::class, 'previewFile'])->can('doc-read')->name('documents.view.preview-file');
Route::get('/list/{id}/view', [\App\Http\Controllers\DMS\DocumentController::class, 'view'])->middleware(['access:my-doc-list'])->can('my-doc-list-access')->name('documents.list.view');

Route::put('/{id}', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'update'])->can('doc-edit')->name('documents.update');
Route::get('/{id}/edit', [\App\Http\Controllers\DMS\MyDocumentContoller::class, 'edit'])->middleware(['access:my-doc-list'])->can('doc-edit')->name('documents.edit');
