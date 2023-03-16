<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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
    return response()->json([]);
});

//Route::get('/home', 'ApiHomeController@getHome');
Route::post('/register', [Controllers\ApiAuthController::class, 'postRegister']);
Route::post('/login', [Controllers\ApiAuthController::class, 'postLogin']);
Route::post('/refresh', [Controllers\ApiAuthController::class, 'postRefresh']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', [Controllers\ApiUserController::class, 'getUser']);
    Route::post('/password', [Controllers\ApiUserController::class, 'postResetPassword']);
    Route::post('/logout', [Controllers\ApiAuthController::class, 'postLogout']);

    Route::group(['prefix' => 'dive-log'], function () {
        Route::get('/', [Controllers\DiveLogController::class, 'getList']);
        Route::post('/', [Controllers\DiveLogController::class, 'postCreate']);
        Route::get('/max-dive-number', [Controllers\DiveLogController::class, 'getMaxDiveNumber']);
    });
});

Route::get('/dive-calculation', [Controllers\DiveCalculatorController::class, 'getCalculation']);
Route::get('/page/home', [Controllers\PageController::class, 'getHomePage']);
Route::get('/page/{id}', [Controllers\PageController::class, 'getPage']);

Route::fallback([\App\Http\Controllers\FallBackController::class, 'fallback']);
