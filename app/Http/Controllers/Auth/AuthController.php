<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin(Request $request): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $token = $request->cookie('auth_token');

        if ($token) {
            $tokenExists = DB::table('personal_access_tokens')
                ->where('token', hash('sha256', $token))
                ->where(function ($query) {
                    $query->where('expires_at', '>', now())
                        ->orWhereNull('expires_at');
                })
                ->exists();

            if ($tokenExists) {
                return redirect()->route('admin.products.index');
            }
        }

        return Inertia::render('Auth/Login');
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        $token = $request->cookie('auth_token');

        if ($token) {
            DB::table('personal_access_tokens')
                ->where('token', hash('sha256', $token))
                ->delete();
        }

        return redirect()->route('home')
            ->withCookie(cookie()->forget('auth_token'));
    }
}
