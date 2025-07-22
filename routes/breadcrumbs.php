<?php

use App\Models\Announcement;
use App\Models\Classes;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Gate;
// user Breadcrumbs
Breadcrumbs::for('user.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('user.dashboard'));
});

Breadcrumbs::for('user.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Users', route('user.index'));
});

Breadcrumbs::for('user.register', function (BreadcrumbTrail $trail) {
    $trail->parent('user.index');
    $trail->push('Register', route('user.register'));
});

Breadcrumbs::for('user.view', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('user.index');
    $trail->push('View User', route('user.view', $user));
});

Breadcrumbs::for('user.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('user.index');
    $trail->push('Edit User', route('user.edit', $user));
});

// class Breadcrumbs
Breadcrumbs::for('class.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Class', route('class.index'));
});

Breadcrumbs::for('class.stream', function (BreadcrumbTrail $trail, $class_id) {
    $encrypted_class_id = $class_id;
    try {
        $class_id = Crypt::decrypt($class_id);
    } catch (DecryptException $e) {
        return redirect()->route('class.index')->withErrors([
            'error' => 'Invalid class awdawdwad',
        ]);
    }

    $class_name = Classes::select('class_name')->find($class_id);
    $trail->parent('class.index');
    $trail->push($class_name->class_name, route('class.stream', $encrypted_class_id));
});

// announcements
Breadcrumbs::for('announcement.index', function (BreadcrumbTrail $trail, $class_id = null, $announcement = null) {
    if ($class_id == 0 && $announcement == 0) {
        $trail->parent('user.dashboard');
        $trail->push(
            'Announcement',
            route('announcement.index', [$class_id, $announcement])
        );
    } else {
        $trail->parent('class.stream', $class_id);
        $trail->push(
            'Announcement',
            route('announcement.index', [$class_id, $announcement])
        );
    }
});


// lessons
Breadcrumbs::for('lesson.index', function (BreadcrumbTrail $trail, $class_id, $lesson_id) {

    $encrypted_class_id = $class_id;
    try {
        $class_id = Crypt::decrypt($class_id);
    } catch (DecryptException $e) {
        return redirect()->route('class.index')->withErrors([
            'error' => 'Invalid class awdawdwad',
        ]);
    }
    $DisplayActions = Gate::allows('admin_lvl1') ? "Create / View " : "View ";

    $trail->parent('class.stream', $encrypted_class_id);
    $trail->push($DisplayActions . 'lessons', route('lesson.index', [$encrypted_class_id, $lesson_id]));
});

// assessments
Breadcrumbs::for('assessment.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Assessments', route('assessment.index'));
});

Breadcrumbs::for('assessment.create', function (BreadcrumbTrail $trail, $class_id) {
    $trail->parent('assessment.index');
    $trail->push('Create Assessments', route('assessment.create', $class_id));
});

Breadcrumbs::for('assessment.edit', function (BreadcrumbTrail $trail, $assessment_id) {
    $trail->parent('assessment.index');
    $trail->push('Edit Assessments', route('assessment.edit', $assessment_id));
});
// progress
Breadcrumbs::for('assessment.show.progress', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Assessment Progress', route('assessment.show.progress'));
});

// courses
Breadcrumbs::for('course.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Courses', route('course.index'));
});

// feedback
Breadcrumbs::for('class.feedback.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Feedback', route('class.feedback.index'));
});

Breadcrumbs::for('class.feedback.create', function (BreadcrumbTrail $trail) {
    $trail->parent('class.feedback.index');
    $trail->push('Create Feedback', route('class.feedback.create'));
});

// Archives
Breadcrumbs::for('class.archives', function (BreadcrumbTrail $trail) {
    $trail->parent('user.dashboard');
    $trail->push('Create Feedback', route('class.archives'));
});
