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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/api-keys', [\App\Http\Controllers\ApiKeyController::class, 'index']);
Route::post('/api-keys/store', [\App\Http\Controllers\ApiKeyController::class, 'store']);
Route::delete('/api-keys/{key}/delete', [\App\Http\Controllers\ApiKeyController::class, 'destroy']);
