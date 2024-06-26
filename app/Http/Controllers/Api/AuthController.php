<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequestUser;
use App\Http\Requests\RegisterRequestUser;

class AuthController extends Controller
{

    public function register(RegisterRequestUser $request): JsonResponse
    {
        $user = new User([
            'email'     => $request->email,
            'name'      => $request->name,
            'password'  => bcrypt($request->password),
        ]);

        $user->save();

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'ok'    => true,
            'user'  => $user,
            'token' => $token
        ]);
    }

    public function login(LoginRequestUser $request): JsonResponse
    {
        $data = $request->only('email', 'password');

        if (!Auth::attempt($data)) {
            return response()->json([
                'ok'    => false,
            ]);
        }

        $token = Auth::user()->createToken('authToken')->accessToken;

        return response()->json([
            'ok'    => true,
            'user'  => Auth::user(),
            'token' => $token
        ]);
    }

}
