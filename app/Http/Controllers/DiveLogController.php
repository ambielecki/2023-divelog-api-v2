<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiveLog\DiveLogIndexRequest;
use App\Http\Requests\DiveLog\DiveLogRequest;
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
            $dive_log->getPaginatedResults($request),
        ));
    }

    public function getMaxDive(Request $request): JsonResponse {
        $user = $request->user();

        $max_dive = DiveLog::where('user_id', $user->id)->max('dive_number');

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            ['max_dive' => $max_dive],
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
                'You are not authorized to read this log',
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

    public function postCreate(DiveLogRequest $request): JsonResponse {
        $log = new DiveLog();
        $log->fillLog($request, true);

        if ($log->save()) {
            return response()->json(JsonResponseData::formatData(
                $request,
                '',
                Message::MESSAGE_OK,
                ['dive_log' => $log],
            ));
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            'Something went wrong updating log, please try again later',
            Message::MESSAGE_ERROR,
            [],
        ), 500);
    }

    public function updateDetails(DiveLogRequest $request, $id): JsonResponse {
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

        if ($user->cannot('update', $log)) {
            return response()->json(JsonResponseData::formatData(
                $request,
                'You are not authorized to update this log',
                Message::MESSAGE_WARNING,
                [],
            ), 403);
        }

        $log->fillLog($request);

        if ($log->save()) {
            return response()->json(JsonResponseData::formatData(
                $request,
                '',
                Message::MESSAGE_OK,
                ['dive_log' => $log],
            ));
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            'Something went wrong updating log, please try again later',
            Message::MESSAGE_ERROR,
            [],
        ), 500);
    }
}
