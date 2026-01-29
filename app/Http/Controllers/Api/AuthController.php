<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Неверный email или пароль'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function me()
    {
        $user = auth()->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout()
    {
        // Удаляем текущий токен пользователя
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Успешный выход']);
    }
}
