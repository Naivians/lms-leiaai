<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [AuthController::class, 'Index'])->name('login');
Route::post('/login', [AuthController::class, 'Login'])->name('auth.login');


Route::middleware(['auth'])->group(function () {
    // users
    Route::get('/userHome', [UserController::class, 'Index'])->name('user.Home');
    Route::get('/Dashboard', [UserController::class, 'Dashboard'])->name('user.Dashboard');
    Route::get('/add', [UserController::class, 'Register'])->name('user.Register');
    Route::post('/user/update', [UserController::class, 'UpdateUser'])->name('user.Update');
    Route::get('/view-user/{userId}', [UserController::class, 'ViewUsers'])->name('user.view');
    Route::get('/user/edit/{userId}', [UserController::class, 'EditUser'])->name('user.Edit');
    Route::post('/user/register', [UserController::class, 'Store'])->name('user.Store');

    // class
    Route::get('/class', [ClassController::class, 'Index'])->name('class.index');
    Route::get('/class/stream', [ClassController::class, 'Stream'])->name('class.stream');
    Route::get('/class/Announcement', [ClassController::class, 'Announcements'])->name('class.announcement');
    Route::get('/class/instructor', [ClassController::class, 'Instructor'])->name('class.instructor');
    Route::get('/class/grade', [ClassController::class, 'Grade'])->name('class.grade');

    // logout
    Route::post('/logout', [AuthController::class, 'Logout'])->name('auth.logout');
});
