<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ValidateController;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)
    ->name('home');

Route::get('{version1?}/{version2?}', [CompareController::class, 'compare'])
    ->name('compare')
    ->where([
        'version1' => '[0-9.]+',
        'version2' => '[0-9.]+',
    ]);
Route::post('diff', [CompareController::class, 'diff'])
    ->name('compare.diff');
Route::post('notes', [CompareController::class, 'notes'])
    ->name('compare.notes');

Route::post('validate', ValidateController::class)
    ->withoutMiddleware(HandleInertiaRequests::class)
    ->name('validate');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('github/callback', [AuthController::class, 'callback'])
        ->name('callback');
    Route::post('github', [AuthController::class, 'redirect'])
        ->name('redirect');
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::resource('notifications', NotificationController::class)
        ->only(['index', 'update', 'destroy']);
    Route::get('notifications/{slug}', [NotificationController::class, 'edit'])
        ->name('notification.edit');
});
