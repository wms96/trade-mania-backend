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
Route::prefix('v1')->group(function () {
    Route::post('auth/register', [App\Http\Controllers\Api\AuthController::class, 'register'])->name('api.auth.register');
    Route::post('auth/login', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.auth.login');

    Route::get('contact', [App\Http\Controllers\Api\ContactController::class, 'index'])->middleware('auth:api')->name('api.contact.get');
    Route::get('chat', [App\Http\Controllers\Api\MessageController::class, 'index'])->middleware('auth:api')->name('api.chat.get');
    Route::get('chats', [App\Http\Controllers\Api\MessageController::class, 'getConversationList'])->middleware('auth:api')->name('api.chats.get');
    Route::post('chat', [App\Http\Controllers\Api\MessageController::class, 'send'])->middleware('auth:api')->name('api.chat.post');
});
