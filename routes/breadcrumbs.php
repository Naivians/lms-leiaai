<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
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
    $trail->parent('class.index');
    $trail->push('Streams', route('class.stream', $class_id));
});

Breadcrumbs::for('announcement.index', function (BreadcrumbTrail $trail, $class_id = null, $announcement = null) {
    $trail->parent('class.stream', $class_id);

    if ($announcement) {
        $trail->push(
            'Announcement: ' . $announcement->title,
            route('announcement.index', [$class_id, $announcement->id])
        );
    } else {
        $trail->push('Announcements');
    }
});
