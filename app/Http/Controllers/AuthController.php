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


        $user = User::where($loginField, $loginInput)->first();

        if (!$user) {
            return back()->withErrors([
                'login_error' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        if ($user->isVerified && !$user->login_status) {
            return back()->withErrors([
                'login_error' => 'Your account has been verified but is not yet activated by the Registrar. Please contact the LEIAAI Registrar for activation.',
            ])->withInput();
        }

        if (!$user->isVerified && !$user->login_status) {
            return back()->withErrors([
                'login_error' => 'Your account is not verified. Please check your email and verify your account.',
            ])->withInput();
        }

        if (auth()->attempt([$loginField => $loginInput, 'password' => $credentials['password']])) {
            return redirect()->intended('/Dashboard');
        }
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

        // dd(Hash::make("admin12345"));

        return view('pages.auth.login');
    }

    public function VerifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return view('pages.errors.404');
        }

        $user->update([
            'isVerified' => true,
            'verification_token' => null,
        ]);

        return view('pages.Auth.email_verification');
    }
}
