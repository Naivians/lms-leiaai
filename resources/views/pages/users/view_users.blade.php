@php
    $title = 'User Detailes';
@endphp

@extends('layouts.app')

@section('header_title', 'User Information')

@section('content')

    <div class="card mb-5">
        <div class="card-body">
            @if ($users)
                <div class="d-flex align-items-start gap-1">
                    <div class="card view_img_container">
                        <img src="{{ asset($users->img) }}">
                    </div>
                    <div class="view_content border border-1 w-100 p-2">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="contact" class="form-label text-secondary">ID Number</label>
                                <input type="text" name="contact" id="contact" class="form-control enable_input"
                                    value="{{ $users->id_number }}" disabled>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="fname" class="form-label text-secondary">Name</label>
                                <input type="text" name="fname" id="fname" class="form-control enable_input"
                                    disabled value="{{ $users->name }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="contact" class="form-label text-secondary">Phone Number</label>
                                <input type="text" name="contact" id="contact" class="form-control enable_input"
                                    disabled value="{{ $users->contact }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label text-secondary">Email Address</label>
                                <input type="text" name="email" id="email" class="form-control enable_input"
                                    disabled value="{{ $users->email }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="contact" class="form-label text-secondary">Gender</label>
                                <input type="text" name="contact" id="contact" class="form-control enable_input"
                                    value="{{ $gender }}" disabled>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="contact" class="form-label text-secondary">Role</label>
                                <input type="text" name="contact" id="contact" class="form-control enable_input"
                                    value="{{ $roles }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($users->role == 0 || $users->role == 1)
                    <div class="card my-2">
                        <div class="card-header d-flex align-items-center">
                            <i class="fa-solid fa-graduation-cap fs-1 me-2"></i>
                            <h2 class="m-0">Class Enrolled</h2>
                        </div>
                    </div>

                    @if (isset($classes) && count($classes) > 0)
                        <div class="class_grid_container mb-5">
                            @php
                                $courses_names = [];
                                foreach ($classes as $course_name) {
                                    $courses_names[] = $course_name->course_name;
                                }
                            @endphp
                            @foreach ($classes as $class)
                                <div class="card">
                                    <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top"
                                        alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $class->class_name }} - {{ $class->course_name }}
                                        </h5>
                                        <p class="card-text">
                                            {{ $class->class_description }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h5 class="text-muted text-center my-4">This user is not enrolled in any classes yet.</h5>
                    @endif

                    @if ($users->role == 0)
                        <div class="card mb-2">
                            <div class="card-header d-flex align-items-center">
                                <i class="fa-solid fa-bookmark fs-1 me-2"></i>
                                <h2 class="m-0">Assessment Progress</h2>
                            </div>
                        </div>

                        @if (isset($assessments) && count($assessments) > 0)
                            @if (isset($courses_names) && count($courses_names) > 0)
                                <div class="my-4">
                                    <label for="course" class="form-label text-secondary">Filter</label>
                                    <select name="course" id="course" class="form-select">
                                        @foreach ($courses_names as $course_name)
                                            <option value="{{ $course_name }}">{{ $course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <table class="table table-striped" id="defaultTable">
                                <thead>
                                    <th class="bg-secondary text-light">Assessment Name</th>
                                    <th class="bg-secondary text-light">Type</th>
                                    <th class="bg-secondary text-light">Total</th>
                                    <th class="bg-secondary text-light">Score</th>
                                    <th class="bg-secondary text-light">Status</th>
                                </thead>
                                <tbody>
                                    @foreach ($assessments as $assessment)
                                        @php
                                            $class = $assessment->status == 'Passed' ? 'text-success' : 'text-danger';
                                        @endphp
                                        <tr>
                                            <td>{{ $assessment->name }}</td>
                                            <td>{{ $assessment->type }}</td>
                                            <td>{{ $assessment->total }}</td>
                                            <td>{{ $assessment->score }}</td>
                                            <td><span class="badge {{ $class }}">{{ $assessment->status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="filterTable"></div>
                        @else
                            <h5 class="text-muted text-center my-4">This student is not yet taken ay assessments</h5>
                        @endif

                    @endif

                @endif
            @else
                <h2>No Data Found!</h2>
            @endif
        </div>
    </div>


@endsection
