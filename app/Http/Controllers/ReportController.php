<?php

namespace App\Http\Controllers;

use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\DiveLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller {
    public function getUserReport(Request $request): JsonResponse {
        $user_query = User::query();
        $total_count = $user_query->count('id');

        $user_query = $user_query->where('created_at', '>=', date('Y-m-d', strtotime('30 days ago')));
        $last_thirty_days = $user_query->count('id');

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            [
                'total'      => $total_count,
                'last_thirty_days' => $last_thirty_days,
            ],
        ));
    }

    public function getDiveLogReport(Request $request): JsonResponse {
        $log_query = DiveLog::query();
        $total_count = $log_query->count('id');

        $log_query = $log_query->where('created_at', '>=', date('Y-m-d', strtotime('30 days ago')));
        $last_thirty_days = $log_query->count('id');

        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            [
                'total'      => $total_count,
                'last_thirty_days' => $last_thirty_days,
            ],
        ));
    }
}
