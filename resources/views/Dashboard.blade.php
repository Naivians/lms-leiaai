@php
    $title = 'Dashboard';
@endphp

@extends('layouts.app')
@section('header_title', 'Dashboard')
@section('content')
    @include('partials.messages', ['title' => 'Permission Denied'])


    @if (Gate::allows('admin_lvl1'))
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase">Students</h6>
                                <h3 class="fw-bold">{{ $studentsCount }} </h3>
                            </div>
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase">Flight Instructor</h6>
                                <h3 class="fw-bold">{{ $fiCount }} </h3>
                            </div>
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase">CGI</h6>
                                <h3 class="fw-bold">{{ $cgiCount }} </h3>
                            </div>
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('class.index') }}">
                        <div class="card text-white bg-primary">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase">Classes</h6>
                                    <h3 class="fw-bold">{{ $classesCount }} </h3>
                                </div>
                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @else
            @if (count($upcomingThisWeek) > 0)
                <div class="card mt-3">

                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-calendar-alt me-2"></i> Upcoming Activities & Quizzes this week
                    </div>

                    <ul class="list-group list-group-flush">
                        @foreach ($upcomingThisWeek as $activity)
                            {{-- Example of activity item --}}
                            <a href="{{ route('class.stream', ['class_id' => Crypt::encrypt($activity->class_id)]) }}" class="text-decoration-none text-dark">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $activity->name }}</h6>
                                        <small class="text-muted"><i
                                                class="fas fa-clock me-1"></i>{{ $activity->assessment_date }}</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">{{ ucfirst($activity->type) }}</span>
                                </li>
                            </a>
                        @endforeach

                    </ul>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                    <strong>No scheduled activities or assessments this week.</strong><br>
                    <small>You're all caught up — check back soon for upcoming tasks.</small>
                </div>
            @endif
        </div>
    @endif

@endsection
