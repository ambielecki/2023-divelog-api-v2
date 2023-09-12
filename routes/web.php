<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::fallback(function (Request $request) {
    return response()->json(\App\Library\JsonResponseData::formatData(
        $request,
        'Sorry, we could not find the endpoint you requested',
        \App\Library\Message::MESSAGE_WARNING,
        [],
    ), 404);
});

