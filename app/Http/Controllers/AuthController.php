<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

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
            return redirect()->intended('/user/Dashboard');
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
            return redirect()->route('user.index');
        }

        // dd(Hash::make("admin12345"));

        return view('pages.auth.login');
    }

    public function RegisterPage()
    {
        return view('pages.Auth.Register');
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

        return view('emails.email_verification');
    }

    public function LoginStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'login_status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }

        $user->login_status = $request->login_status;
        $user->save();

        $message = $request->login_status == 1
            ? "Account successfully activated"
            : "Account successfully deactivated";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }



    public function ForgotVerifyEmail(Request $request)
    {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }


    public function PasswordResetForm(Request $request)
    {
        return view('pages.Auth.password_reset', ['request' => $request]);
    }

    public function PasswordReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
