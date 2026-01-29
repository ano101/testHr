<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin(): \Inertia\Response
    {
        return Inertia::render('Auth/Login');
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home');
    }
}
