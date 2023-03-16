<?php

use Illuminate\Support\Facades\Route;

Route::get('test', [\App\Http\Controllers\TestController::class, 'getTest']);

Route::fallback([\App\Http\Controllers\FallBackController::class, 'fallback']);
