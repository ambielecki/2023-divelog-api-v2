<?php

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

Route::get('/', function (\Illuminate\Http\Request $request) {
    return response()->json(\App\Library\JsonResponseData::formatData($request, 'Health OK'));
});

if (config('app.env') === 'local') {
    Route::get('/test', [Controllers\TestController::class, 'getTest']);
}

Route::post('/register', [Controllers\ApiAuthController::class, 'postRegister']);
Route::post('/login', [Controllers\ApiAuthController::class, 'postLogin']);
Route::post('/refresh', [Controllers\ApiAuthController::class, 'postRefresh']);
Route::post('/request-password-reset', [Controllers\ApiAuthController::class, 'postRequestPasswordReset']);
Route::post('/password-reset', [Controllers\ApiAuthController::class, 'postResetPassword']);

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
        Route::delete('/{id}', [Controllers\DiveLogController::class, 'delete']);
    });

    Route::group(['middleware' => ['admin'], 'prefix' => '/admin'], function () {
        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

        Route::post('home', [Controllers\PageController::class, 'postHomePage']);
        Route::post('blog', [Controllers\PageController::class, 'postBlogPage']);

        Route::group(['prefix' => '/image'], function () {
            Route::get('/', [Controllers\ImageController::class, 'getIndex']);
            Route::post('/', [Controllers\ImageController::class, 'postUpload']);
            Route::patch('/{id}', [Controllers\ImageController::class, 'patchUpdate']);
        });

        Route::group(['prefix' => 'tag'], function () {
            Route::get('/', [Controllers\TagController::class, 'getIndex']);
        });
    });
});

Route::get('/dive-calculation', [Controllers\DiveCalculatorController::class, 'getCalculation']);
Route::get('/dive-tables', [Controllers\DiveCalculatorController::class, 'getTableData']);
Route::get('/page/home', [Controllers\PageController::class, 'getHomePage']);

Route::group(['prefix' => 'blog'], function () {
    Route::get('/{slug}', [Controllers\PageController::class, 'getBlogPage']);
    Route::get('/', [Controllers\PageController::class, 'getBlogList']);
});

