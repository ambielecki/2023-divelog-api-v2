<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiveLogCreateRequest;
use App\Library\JsonResponseData;
use App\Models\DiveLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiveLogController extends Controller
{
    public function getList(Request $request): JsonResponse
    {
        $dive_log = new DiveLog();
        $query = $dive_log->newQuery();

        return $dive_log->getResponse($request, $query);
    }

    public function postCreate(DiveLogCreateRequest $request): JsonResponse
    {
        return response()->json();
    }

    public function getMaxDiveNumber(Request $request) : JsonResponse
    {
        $dive_log = new DiveLog();

        return $dive_log->getMaxDiveNumberResponse($request);
    }
}
