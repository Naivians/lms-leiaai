<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
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


Route::get('/', function () {
    return view('welcome');
});

// Auth Controllers
// users
Route::get('/userHome', [UserController::class, 'Index'])->name('user.Home');
Route::get('/add', [UserController::class, 'AddUsers'])->name('user.Store');
Route::get('/view-user/{userId}', [UserController::class, 'ViewUsers'])->name('user.view');

// class
Route::get('/class', [ClassController::class, 'Index'])->name('class.index');
Route::get('/class/stream', [ClassController::class, 'Stream'])->name('class.stream');
Route::get('/class/Announcement', [ClassController::class, 'Announcements'])->name('class.announcement');
Route::get('/class/instructor', [ClassController::class, 'Instructor'])->name('class.instructor');
Route::get('/class/grade', [ClassController::class, 'Grade'])->name('class.grade');
