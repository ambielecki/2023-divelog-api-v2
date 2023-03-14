<?php

namespace App\Http\Controllers;

use App\Library\JsonResponseData;
use App\Library\Message;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth;
use Illuminate\Support\Facades\Log;

class ApiUserController extends Controller
{
    public function getUser(Request $request): JsonResponse {
        return response()->json(JsonResponseData::formatData(
            $request,
            '',
            Message::MESSAGE_OK,
            $request->user()->toArray()
        ));
    }

    public function postResetPassword(Request $request): JsonResponse {
        $user = JWTAuth::getToken() ? JWTAuth::parseToken()->toUser() : null;

        if ($user) {
            if ($user->email === 'testy@test.com' || $user->email === 'automation@test.com') {
                return response()->json(JsonResponseData::formatData(
                    $request,
                    'Do not mess with my test user :)',
                    Message::MESSAGE_ERROR,
                    [],
                ), 401);
            }
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                try {
                    $user->save();
                } catch (Exception $exception) {
                    Log::error($exception);

                    return response()->json(JsonResponseData::formatData(
                        $request,
                        'There was a problem updating your password',
                        Message::MESSAGE_ERROR,
                        [],
                    ), 500);
                }

                return response()->json(JsonResponseData::formatData(
                    $request,
                    'Password Updated Successfully',
                    Message::MESSAGE_OK,
                    [],
                ));
            }

            return response()->json(JsonResponseData::formatData(
                $request,
                'Current Password Is Incorrect',
                Message::MESSAGE_WARNING,
                [],
            ), 401);
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            'There was a problem finding your user',
            Message::MESSAGE_ERROR,
            [],
        ), 500);
    }
}
