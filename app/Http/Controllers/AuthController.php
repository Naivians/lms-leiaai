<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $credentials['login'];

        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'id_number';

        if (auth()->attempt([$loginField => $loginInput, 'password' => $credentials['password']])) {
            return redirect()->intended('/Dashboard');
        }

        return back()->withErrors([
            'login_error' => 'The provided credentials do not match our records.',
        ])->withInput();
    }


    public function Logout()
    {
        Auth::logout();
        return response()->json(['location' => '/']);
    }

    public function Index()
    {
        if (Auth::check()) {
            return redirect()->route('user.Home');
        }
        return view('pages.auth.login');
    }
}
