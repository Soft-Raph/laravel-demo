<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('register', [\App\Http\Controllers\RegisterController::class, 'register'])->name('register');
Route::get('api-keys', [\App\Http\Controllers\ApiKeyController::class, 'index'])->name('api-keys');
Route::post('api-keys/store', [\App\Http\Controllers\ApiKeyController::class, 'store'])->name('api-keys.store');
Route::delete('api-keys/{key}/delete', [\App\Http\Controllers\ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
