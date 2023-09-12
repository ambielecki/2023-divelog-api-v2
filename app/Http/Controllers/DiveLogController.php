<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiveLog\DiveLogIndexRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\DiveLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiveLogController extends Controller {
    public function getIndex(DiveLogIndexRequest $request): JsonResponse {
        $dive_log = new DiveLog();

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            $dive_log->getLogs($request),
        ));
    }

    public function getDetails(Request $request, $id): JsonResponse {
        $log = DiveLog::find($id);
        if (!$log) {
            return response()->json(JsonResponseData::formatData(
                $request,
                'Dive log not found',
                Message::MESSAGE_WARNING,
                [],
            ), 404);
        }

        $user = $request->user();

        if ($user->cannot('read', $log)) {
            return response()->json(JsonResponseData::formatData(
                $request,
                'You are not authorized to update this log',
                Message::MESSAGE_WARNING,
                [],
            ), 403);
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            ['dive_log' => $log],
        ));
    }
}
