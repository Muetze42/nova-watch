<?php

use App\Http\Controllers\Api\CheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return [
        'message' => 'It work’s!',
        'time' => now(),
    ];
})->name('check-reachability');

Route::get('update-check/{version}', CheckController::class)
    ->name('update-check');
