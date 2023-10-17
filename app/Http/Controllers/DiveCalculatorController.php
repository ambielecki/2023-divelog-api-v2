<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\DiveCalculatorRequest;
use App\Library\DiveCalculator;
use App\Library\JsonResponseData;
use App\Library\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiveCalculatorController extends Controller {
    public function getCalculation(DiveCalculatorRequest $request): JsonResponse {
        $dive_calculator = new DiveCalculator();
        $calculation = $dive_calculator->handleApiRequest(
            $request->input('dive_1_depth') ?: null,
            $request->input('dive_1_time') ?: null,
            $request->input('surface_interval') ?: null,
            $request->input('dive_2_depth') ?: null,
            $request->input('dive_2_time') ?: null,
        );

        return response()->json(
            JsonResponseData::formatData($request, 'DiveCalculation', Message::MESSAGE_OK, $calculation)
        );
    }

    public function getTableData(Request $request): JsonResponse {
        $dive_calculator = new DiveCalculator();

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            [
                'dive_tables' => [
                    'depths'      => $dive_calculator->getTableDepths(),
                    'groups'      => $dive_calculator->getTableGroups(),
                    'table_one'   => $dive_calculator->getTableOne(),
                    'table_two'   => $dive_calculator->getTableTwo(),
                    'table_three' => $dive_calculator->getTableThree(),
                ],
            ],
        ));
    }
}
