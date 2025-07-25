<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseModelController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\AssessmentController;

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
    Route::post('update/account_verification_status', [AuthController::class, 'verificationStatus'])->name('auth.Login.verificationStatus');

    // users
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'Index'])->name('index');
        Route::get('/Register', [UserController::class, 'Register'])->name('register');
        Route::get('/Dashboard', [UserController::class, 'Dashboard'])->name('dashboard');
        Route::post('/update', [UserController::class, 'UpdateUser'])->name('update');
        Route::get('/view-user/{userId}', [UserController::class, 'ViewUsers'])->name('view');
        Route::get('/edit/{userId}', [UserController::class, 'EditUser'])->name('edit');
    });

    // class
    Route::prefix('class')->name('class.')->group(function () {
        Route::get('/', [ClassController::class, 'Index'])->name('index');
        Route::get('/search', [ClassController::class, 'Search'])->name('search');
        Route::get('/archives', [ClassController::class, 'Archives'])->name('archives');
        Route::get('/stream/{class_id}', [ClassController::class, 'Stream'])->name('stream');
        Route::get('/show/{classId}', [ClassController::class, 'Show'])->name('show');
        Route::get('/getEnrolledUsers/{class_id}', [ClassController::class, 'getEnrolledUsers'])->name('enrolled');
        Route::get('/instructor', [ClassController::class, 'Instructor'])->name('instructor');
        Route::get('/grade', [ClassController::class, 'Grade'])->name('grade');
        Route::get('/feedback', [ClassController::class, 'feedbackIndex'])->name('feedback.index');
        Route::get('/feedback/create', [ClassController::class, 'createFeedback'])->name('feedback.create');

        Route::post('/feedback/save', [ClassController::class, 'storeFeedback'])->name('feedback.store');
        Route::post('/create', [ClassController::class, 'Store'])->name('store');
        Route::post('/update', [ClassController::class, 'Update'])->name('update');
        Route::post('/archive/{classId}', [ClassController::class, 'ArchiveClass'])->name('archive');
        Route::post('/enroll', [ClassController::class, 'Enroll'])->name('enroll');
        Route::post('/remove-user-from-cLass', [ClassController::class, 'RemoveUserFromClass'])->name('remove-user');
    });

    Route::prefix('announcement')->name('announcement.')->group(function () {
        Route::get('/{class_id}/{announcement_id}', [AnnouncementController::class, 'index'])->name('index');

        Route::post('/store', [AnnouncementController::class, 'store'])->name('store');
        Route::post('/update', [AnnouncementController::class, 'update'])->name('update');
        Route::delete('/delete-announcement/{announcementId}', [AnnouncementController::class, 'destroy'])->name('destroy');
    });

    // course models
    Route::prefix('course')->name('course.')->group(function () {
        Route::get('/', [CourseModelController::class, 'Index'])->name('index');
        Route::post('/create', [CourseModelController::class, 'Create'])->name('create');
        Route::get('/show/{courseId}', action: [CourseModelController::class, 'Show'])->name('view');
        Route::get('/edit/{courseId}', [CourseModelController::class, 'Edit'])->name('edit');
        Route::post('/update', [CourseModelController::class, 'Update'])->name('update');
        Route::post('/delete/{courseId}', [CourseModelController::class, 'Destroy'])->name('delete');
    });

    // lessons
    Route::prefix('lessons')->name('lesson.')->group(function () {
        Route::get('/lessons/{class_id}/{lesson_id}', [LessonsController::class, 'index'])->name('index');
        Route::get('/show/{lesson_id}', [LessonsController::class, 'Show'])->name('show');
        Route::get('/edit/{lesson_id}', [LessonsController::class, 'Edit'])->name('edit');
        Route::get('/view-pdf/{pdf_url}', [LessonsController::class, 'viewPDF'])->name('pdf');

        Route::post('/store', [LessonsController::class, 'store'])->name('store');
        Route::post('/update', [LessonsController::class, 'update'])->name('update');
        Route::post('/deleteMaterials/{lesson_id}', [LessonsController::class, 'Destroy'])->name('delete');
        Route::post('/deleteLesson/{lesson_id}', [LessonsController::class, 'deleteLesson'])->name('deleteLesson');
    });

    // assessments
    Route::prefix('assessments')->name('assessment.')->group(function () {
        Route::get('/', [AssessmentController::class, 'index'])->name('index');
        Route::get('/create/{class_id}', [AssessmentController::class, 'create'])->name('create');
        Route::get('/show/{assessment_id}', [AssessmentController::class, 'show'])->name('show');
        Route::get('/edit/{assessment_id}', [AssessmentController::class, 'edit'])->name('edit');
        Route::get('/assessment/{assessment_id}', [AssessmentController::class, 'takeAssessment'])->name('take');
        Route::post('/store', [AssessmentController::class, 'store'])->name('store');
        Route::post('/update', [AssessmentController::class, 'update'])->name('update');
        Route::post('/destroyQuestion', [AssessmentController::class, 'destroyQuestion'])->name('destroy.question');
        Route::post('/destroyAssessment/{assessment_id}', [AssessmentController::class, 'destroy'])->name('destroy');
        Route::post('/save_assessment', [AssessmentController::class, 'saveAssessments'])->name('save.assessments');

        // progress
        Route::get('/progress', [AssessmentController::class, 'progress'])->name('show.progress');
        Route::get('/progress/{progress_id}', [AssessmentController::class, 'viewProgress'])->name('view.progress');

    });

    // logout
    Route::post('/logout', [AuthController::class, 'Logout'])->name('auth.logout');


    Route::fallback(function () {
        return response()->view('pages.errors.404', [['user' => true]], 404);
    });
});

Route::fallback(function () {
    return response()->view('pages.errors.404', ['user' => false], 404);
});
