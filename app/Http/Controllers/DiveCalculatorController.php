<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiveCalculatorRequest;
use App\Library\DiveCalculator;
use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Http\JsonResponse;

class DiveCalculatorController extends Controller
{
    public function getCalculation(DiveCalculatorRequest $request): JsonResponse
    {
        $dive_calculator = new DiveCalculator();
        $calculation = $dive_calculator->handleApiRequest(
            $request->input('dive_1_depth') ?: null,
            $request->input('dive_1_time') ?: null,
            $request->input('surface_interval') ?: null,
            $request->input('dive_2_depth') ?: null,
            $request->input('dive_2_time') ?: null,
        );

        return response()->json([
            JsonResponseData::formatData($request, 'DiveCalculation', Message::MESSAGE_OK, $calculation)
        ]);
    }
}
