<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\HomeController;
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
Route::get('{version1?}/{version2?}', CompareController::class)
    ->name('compare');

Route::post('validate', ValidateController::class)
    ->withoutMiddleware(HandleInertiaRequests::class)
    ->name('validate');

Route::get('auth/github/callback', [AuthController::class, 'callback'])
    ->name('auth.callback');
Route::post('auth/github', [AuthController::class, 'redirect'])
    ->name('auth.redirect');
