<?php

namespace App\Http\Controllers;

use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth;
class ApiAuthController extends Controller
{
    public function postRegister(Request $request): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken(
            $request,
            $token,
            'Thank you for registering',
            Message::MESSAGE_SUCCESS
        );
    }

    public function postLogin(Request $request): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(JsonResponseData::formatData(
                $request,
                'User/password not found, please try again',
                Message::MESSAGE_ERROR,
                [],
            ), 401);
        }

        return $this->respondWithToken($request, $token, 'Successfully Logged In', Message::MESSAGE_SUCCESS);
    }

    public function postLogout(Request $request): JsonResponse
    {
        auth('api')->logout();

        return response()->json(JsonResponseData::formatData(
            $request,
            'Successfully logged out',
            Message::MESSAGE_SUCCESS,
            [],
        ));
    }

    public function postRefresh(Request $request): JsonResponse
    {
        return $this->respondWithToken($request, auth('api')->refresh());
    }

    public function getPleaseLogIn(Request $request): JsonResponse
    {
        return response()->json(JsonResponseData::formatData(
            $request,
            'You Must Be Logged In to View this Page',
            Message::MESSAGE_ERROR,
            [],
        ), 401);
    }

    protected function respondWithToken(Request $request, $token, $message = 'Success', $message_type = Message::MESSAGE_OK): JsonResponse
    {
        return response()->json(JsonResponseData::formatData(
            $request,
            $message,
            $message_type,
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL(),
            ]
        ));
    }
}
