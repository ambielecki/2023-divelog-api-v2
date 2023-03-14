<?php

namespace App\Library;

use Illuminate\Http\Request;
class JsonResponseData {
    public static function formatData(
        Request $request,
        string $message = '',
        string $message_type = Message::MESSAGE_OK,
        array $data = []
    ): array
    {
        return [
            'message' => $message,
            'message_type' => $message_type,
            'request_info' => [
                'route' => $request->path(),
                'method' => $request->method(),
                'date' => gmdate('Y-m-d H:i:s e'),
            ],
            'data' =>  $data,
        ];
    }
}
