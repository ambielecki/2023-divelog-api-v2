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

Route::post('/register', [Controllers\ApiAuthController::class, 'postRegister']);
Route::post('/login', [Controllers\ApiAuthController::class, 'postLogin']);
Route::post('/refresh', [Controllers\ApiAuthController::class, 'postRefresh']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', [Controllers\ApiUserController::class, 'getUser']);
    Route::post('/password', [Controllers\ApiUserController::class, 'postResetPassword']);
    Route::post('/logout', [Controllers\ApiAuthController::class, 'postLogout']);

    Route::group(['prefix' => '/dive-log'], function () {
        Route::get('/', [Controllers\DiveLogController::class, 'getIndex']);
        Route::post('/', [Controllers\DiveLogController::class, 'postCreate']);
        Route::get('/max-dive', [Controllers\DiveLogController::class, 'getMaxDive']);
        Route::get('/{id}', [Controllers\DiveLogController::class, 'getDetails']);
        Route::put('/{id}', [Controllers\DiveLogController::class, 'updateDetails']);
    });

    Route::group(['middleware' => ['admin'], 'prefix' => '/admin'], function () {
        Route::group(['prefix' => '/image'], function () {
            Route::get('/', [Controllers\ImageController::class, 'getIndex']);
            Route::post('/', [Controllers\ImageController::class, 'postUpload']);
        });

        Route::group(['prefix' => 'tag'], function () {
            Route::get('/', [Controllers\TagController::class, 'getIndex']);
        });
    });
});

Route::get('/dive-calculation', [Controllers\DiveCalculatorController::class, 'getCalculation']);
Route::get('/page/home', [Controllers\PageController::class, 'getHomePage']);
Route::get('/page/{id}', [Controllers\PageController::class, 'getPage']);
