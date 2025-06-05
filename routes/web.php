<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



Route::get('/', [AuthController::class, 'Index'])->name('login');
Route::post('/login', [AuthController::class, 'Login'])->name('auth.login');
Route::get('/user/register', [AuthController::class, 'RegisterPage'])->name('auth.Register');
Route::post('/user/register', [UserController::class, 'Store'])->name('user.Store');

Route::get('/login/verification/{token}', [AuthController::class, 'VerifyEmail'])
->name('auth.email.verify');

Route::post('user/forgot-password', [AuthController::class, 'ForgotVerifyEmail'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'PasswordResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'PasswordReset'])->name('password.update');

Route::get('/forgot-password', function () {
    return view('pages.Auth.forgot-password');
})->name('auth.password.request');

// Route::get('/reset-password', function (Request $request) {
//     return view('pages.Auth.password_reset', ['request' => $request]);
// })->name('password.reset');

// Handle Reset Password form submission
// Route::post('/reset-password', function (Request $request) {

// })->name('password.update');

Route::middleware(['auth'])->group(function () {

    // Auth
    Route::post('update/login_status', [AuthController::class, 'LoginStatus'])->name('auth.Login.Status');

    // users
    Route::get('/Register', [UserController::class, 'Register'])->name('user.Register');
    Route::get('/userHome', [UserController::class, 'Index'])->name('user.Home');
    Route::get('/Dashboard', [UserController::class, 'Dashboard'])->name('user.Dashboard');
    Route::post('/user/update', [UserController::class, 'UpdateUser'])->name('user.Update');
    Route::get('/view-user/{userId}', [UserController::class, 'ViewUsers'])->name('user.view');
    Route::get('/user/edit/{userId}', [UserController::class, 'EditUser'])->name('user.Edit');

    // class
    Route::get('/class', [ClassController::class, 'Index'])->name('class.index');
    Route::get('/class/stream', [ClassController::class, 'Stream'])->name('class.stream');
    Route::get('/class/Announcement', [ClassController::class, 'Announcements'])->name('class.announcement');
    Route::get('/class/instructor', [ClassController::class, 'Instructor'])->name('class.instructor');
    Route::get('/class/grade', [ClassController::class, 'Grade'])->name('class.grade');

    // logout
    Route::post('/logout', [AuthController::class, 'Logout'])->name('auth.logout');
});
