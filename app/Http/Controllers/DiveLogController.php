<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiveLogController extends Controller
{
    public function getIndex(Request $request): JsonResponse {
        return response()->json();
    }
}
