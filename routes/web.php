<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', fn() => redirect('/sign-in'));
Route::get('/login', fn() => redirect('/sign-in'))->name('login');

// authentication
Route::get('/sign-in', [\App\Http\Controllers\Account\AuthenticationController::class, 'index'])->name('sign-in.index');
Route::post('/sign-in', [\App\Http\Controllers\Account\AuthenticationController::class, 'signIn'])->name('sign-in');
Route::get('/sign-out', [\App\Http\Controllers\Account\AuthenticationController::class, 'signOut'])->name('sign-out');

// registration
Route::get('/sign-up', [\App\Http\Controllers\Config\UserController::class, 'indexRegister'])->middleware('guest')->name('sign-up');
Route::post('/sign-up', [\App\Http\Controllers\Config\UserController::class, 'storeRegister'])->middleware('guest')->name('sign-up.register');
Route::get('/email/verify', [\App\Http\Controllers\Config\UserController::class, 'indexVerificationEmailNotice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Config\UserController::class, 'updateRegisterVerification'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [\App\Http\Controllers\Config\UserController::class, 'resendVerificationLink'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// forgot password
Route::get('/forgot-password', [\App\Http\Controllers\Config\UserController::class, 'indexForgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Config\UserController::class, 'updateForgotPassword'])->middleware('guest')->name('password.email');
Route::post('/reset-password', [\App\Http\Controllers\Config\UserController::class, 'updateWithNewPassword'])->middleware('guest')->name('password.update');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Config\UserController::class, 'indexResetPassword'])->middleware('guest')->name('password.reset');

// change profile
Route::get('/profile', [\App\Http\Controllers\Account\ProfileController::class, 'profileInfo'])->middleware(['auth', 'verified']);
Route::get('/change-profile', [\App\Http\Controllers\Account\ProfileController::class, 'profileIndex'])->middleware(['auth', 'verified', 'access:null,0'])->name('change-profile');
Route::post('/change-profile', [\App\Http\Controllers\Account\ProfileController::class, 'profileSave'])->middleware(['auth', 'verified'])->name('change-profile-save');

// change password
Route::get('/change-password', [\App\Http\Controllers\Account\ProfileController::class, 'changePasswordIndex'])->middleware(['auth', 'verified', 'access:null,0'])->name('change-password');
Route::post('/change-password', [\App\Http\Controllers\Account\ProfileController::class, 'changePasswordSave'])->middleware(['auth', 'verified'])->name('change-password-save');

// sign-in history
Route::get('/sign-in-history', [\App\Http\Controllers\Account\ProfileController::class, 'indexSignInHistory'])->middleware(['auth', 'verified', 'access:null,0'])->name('sign-in-history');

// cache clear
Route::get('/cache-clear', function () {
    $clearCommands = ['optimize:clear', 'cache:clear', 'config:clear', 'event:clear', 'route:clear', 'view:clear'];
    $cacheCommands = ['config:cache', 'event:cache', 'route:cache', 'view:cache', 'data:cache-structures'];

    if (!app()->isProduction()) {
        return response()->json(["message" => "Cache for production only."])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // clear cache
    foreach ($clearCommands as $command) {
        Artisan::call($command);
    }

    // cache
    foreach ($cacheCommands as $command) {
        Artisan::call($command);
    }

    // clear
    Cache::forget('pages');

    return response()->json(["message" => "Cache successfully cleared."])->setStatusCode(Response::HTTP_OK);
})->middleware(['auth']);

// options
Route::prefix('options')->middleware(['auth'])->group(function () {
    Route::get('/configs/users', [\App\Http\Controllers\Config\UserController::class, 'options']);
    Route::get('/basics/categories', [\App\Http\Controllers\Basic\CategoryController::class, 'options']);
    Route::get('/basics/category-subs', [\App\Http\Controllers\Basic\CategorySubController::class, 'options']);
    Route::get('/basics/approval-sets/{approvalSetId?}', [\App\Http\Controllers\Basic\ApprovalSetController::class, 'options']);
});

// dataTable
Route::prefix('dt')->middleware(['auth'])->group(fn() => require_once __DIR__ . '/web_datatable.php');
Route::prefix('lov')->middleware(['auth'])->group(fn() => require_once __DIR__ . '/web_lov.php');

// config
Route::prefix('configs')->middleware(['auth', 'can:config'])->group(fn() => require_once __DIR__ . '/web_config.php');

// basic
Route::prefix("basics")->middleware(['auth'])->group(fn() => require_once __DIR__ . '/web_basic.php');

// document
Route::prefix("documents")->middleware(['auth'])->group(fn() => require_once __DIR__ . '/web_dms.php');

// download template
Route::get('/templates/{template_name}', function (string $template_name) {
    // variables
    $fileWithPath = config('setting.other.path_to_template') . '/' . $template_name;

    // check if file exist
    if (!Storage::disk('public')->exists($fileWithPath)) {
        abort(Response::HTTP_NOT_FOUND);
    }

    return Storage::disk('public')->download($fileWithPath, $template_name);
})->name('template.download');

// download file temp like exported data
Route::get('/download-temp/{fileNameEncoded}', function (string $fileNameEncoded) {
    // decode
    $fileName = base64_decode($fileNameEncoded);

    // get file path
    $fileWithPath = config('setting.other.path_to_temp') . '/' . $fileName;

    // check if file exist
    if (!Storage::disk('local')->exists($fileWithPath)) {
        abort(Response::HTTP_NOT_FOUND);
    }

    return response()->download(Storage::disk('local')->path($fileWithPath), $fileName)->deleteFileAfterSend();
})->name('download-temp-file');
