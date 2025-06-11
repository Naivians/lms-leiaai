<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseModelController;

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

Route::middleware(['auth'])->group(function () {
    // Auth
    Route::post('update/login_status', [AuthController::class, 'LoginStatus'])->name('auth.Login.Status');

    // users
    Route::prefix('user')->name('user.')->group(function (){
        Route::get('/', [UserController::class, 'Index'])->name('index');
        Route::get('/Register', [UserController::class, 'Register'])->name('register');
        Route::get('/Dashboard', [UserController::class, 'Dashboard'])->name('dashboard');
        Route::post('/update', [UserController::class, 'UpdateUser'])->name('update');
        Route::get('/view-user/{userId}', [UserController::class, 'ViewUsers'])->name('view');
        Route::get('/edit/{userId}', [UserController::class, 'EditUser'])->name('edit');
    });

    // class
    Route::prefix('class')->name('class.')->group(function (){
        Route::get('/', [ClassController::class, 'Index'])->name('index');
        Route::get('/archives', [ClassController::class, 'Archives'])->name('archives');
        Route::get('/Announcement', [ClassController::class, 'Announcements'])->name('announcement');
        Route::get('/stream/{class_id}', [ClassController::class, 'Stream'])->name('stream');
        Route::post('/create', [ClassController::class, 'Store'])->name('store');
        Route::get('/show/{classId}', [ClassController::class, 'Show'])->name('show');
        Route::get('/getEnrolledUsers/{class_id}', [ClassController::class, 'getEnrolledUsers'])->name('enrolled');
        Route::post('/update', [ClassController::class, 'Update'])->name('update');
        Route::post('/archive/{classId}', [ClassController::class, 'ArchiveClass'])->name('archive');
        Route::get('/instructor', [ClassController::class, 'Instructor'])->name('instructor');
        Route::get('/grade', [ClassController::class, 'Grade'])->name('grade');
    });


    // course models
    Route::prefix('course')->name('course.')->group(function(){
        Route::get('/', [CourseModelController::class, 'Index'])->name('index');
        Route::post('/create', [CourseModelController::class, 'Create'])->name('create');
        Route::get('/show/{courseId}', [CourseModelController::class, 'Show'])->name('view');
        Route::get('/edit/{courseId}', [CourseModelController::class, 'Edit'])->name('edit');
        Route::post('/update', [CourseModelController::class, 'Update'])->name('update');
        Route::post('/delete/{courseId}', [CourseModelController::class, 'Destroy'])->name('delete');
    });

    // logout
    Route::post('/logout', [AuthController::class, 'Logout'])->name('auth.logout');
});
