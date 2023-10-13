<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestPasswordResetRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Library\JsonResponseData;
use App\Library\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function postRegister(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (\Exception $exception) {
            return response()->json(JsonResponseData::formatData(
                $request,
                'Failed Creating New User',
                Message::MESSAGE_ERROR,
            ), 409);
        }


        $token = auth('api')->login($user);

        return $this->respondWithToken(
            $request,
            $token,
            'Thank you for registering',
            Message::MESSAGE_SUCCESS,
            $user,
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

    protected function respondWithToken(Request $request, $token, $message = 'Success', $message_type = Message::MESSAGE_OK, $user = null): JsonResponse
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL(),
        ];

        if ($user) {
            $data['user'] = $user;
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            $message,
            $message_type,
            $data,
        ));
    }

    public function postRequestPasswordReset(RequestPasswordResetRequest $request): JsonResponse {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(JsonResponseData::formatData(
                $request,
                __($status),
                Message::MESSAGE_SUCCESS,
                [],
            ));
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            __($status),
            Message::MESSAGE_ERROR,
            [],
        ), 500);
    }

    public function postResetPassword(ResetPasswordRequest $request): JsonResponse {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(JsonResponseData::formatData(
                $request,
                __($status),
                Message::MESSAGE_SUCCESS,
                [],
            ));
        }

        return response()->json(JsonResponseData::formatData(
            $request,
            __($status),
            Message::MESSAGE_ERROR,
            [],
        ), 500);
    }
}
