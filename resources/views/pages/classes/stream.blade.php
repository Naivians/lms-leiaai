@php
    $title = 'Streams';
@endphp

@extends('layouts.app')

@section('content')
    <input type="hidden" name="encrypted_class_id" id="encrypted_class_id" value="{{ $class_id ?? '' }}">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="classTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="streams" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab"
                aria-controls="tab1" aria-selected="true">
                Streams
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="classwork" data-bs-toggle="tab" data-bs-target="#tab2" type="button"
                role="tab" aria-controls="tab2" aria-selected="false">
                Classwork
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="people" data-bs-toggle="tab" data-bs-target="#tab3" type="button"
                role="tab" aria-controls="tab3" aria-selected="false">
                People
            </button>
        </li>

        @if (Auth::user()->role === 4 || Auth::user()->role === 5)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="grade" data-bs-toggle="tab" data-bs-target="#tab4" type="button"
                    role="tab" aria-controls="tab4" aria-selected="false">
                    Grade
                </button>
            </li>
        @endif
    </ul>

    <div class="tab-content mt-3">
        {{-- streams --}}
        <div class="tab-pane fade" id="tab1" role="tabpanel" aria-labelledby="streams">
            <a href="{{ route('class.announcement') }}" class="text-decoration-none">
                <div class="announce_form-controller my-3">
                    <div class="google-classroom-announce announce_btn">
                        <div class="announce-text w-100">
                            <div>
                                <div class="d-flex align-items-center">
                                    <div class="announcement_header">
                                        <div class="announcement_img_container">
                                            <img src="{{ asset('assets/img/pilot.png') }}" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <p class=" mx-2 my-0">Announce something to your class</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <div class="card my-3">
                <div class="card-header announcement_header">
                    <div class="announcement_header">
                        <div class="announcement_img_container">
                            <img src="{{ asset('assets/img/pilot.png') }}" alt="">
                        </div>
                        <div>
                            <h5 class="mx-2 my-0">Teachers Name</h5>
                            <small class="mx-2">May 13, 2025</small>
                        </div>
                    </div>
                    <div class="edit_btn">
                        {{-- edit_announcementForm --}}
                        <i class="fa-solid fa-pen-to-square btn btn-outline-warning" onclick="edit_announcement(this)"
                            data-id="123" data-content="<p>This is your announcement content</p>">
                        </i>
                        <i class="fa-solid fa-trash btn btn-outline-danger" onclick="delete_announcement(2)"></i>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, cupiditate.</p>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-header announcement_header">
                    <div class="announcement_header">
                        <div class="announcement_img_container">
                            <img src="{{ asset('assets/img/pilot.png') }}" alt="">
                        </div>
                        <div>
                            <h5 class="mx-2 my-0">Teachers Name</h5>
                            <small class="mx-2">May 12, 2025</small>
                        </div>
                    </div>
                    <div class="edit_btn">
                        <i class="fa-solid fa-pen-to-square btn btn-outline-warning"></i>
                        <i class="fa-solid fa-trash btn btn-outline-danger"></i>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, cupiditate.</p>
                </div>
            </div>
        </div>
        {{-- classwork --}}
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="classwork">
            <div class="my-3 p-3">
                <h2 class="text-secondary mb-3 bg-light p-3 rounded">Lessons</h2>
                <table id="classwork_lessons_table" class="display w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Suffix</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="p-3">
                <h2 class="text-secondary mb-3 bg-light p-3 rounded">Quizses / Exams</h2>
                <table id="classwork_quiz_table" class="display w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Suffix</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        {{-- people --}}
        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="people">

            @if (Auth::user()->not_for_sp_fi())
                <div class="announce_form-controller my-3" style="cursor: pointer;" id="enroll_fi_container">
                    <div class="google-classroom-announce announce_btn">
                        <div class="announce-text w-100">
                            <div class="d-flex align-items-center">
                                <div class="announcement_header">
                                    <div class="announcement_img_container">
                                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="leiaai logo">
                                    </div>
                                </div>
                                <div>
                                    <h5 class=" mx-2 my-0">Enroll FI / CGI</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3 d-none" id="enroll_fi_form">
                    <input type="search" class="form-control" id="search_fi" placeholder="Search FI or CG"
                        aria-label="Enroll FI or CGI" aria-describedby="basic-addon1">
                    <button type="button" class="btn btn-outline-danger" id="close_enroll_fi_btn">Close</button>
                </div>
                <div class=" border border-2 my-2 p-3" id="searchContainer">
                    <div class="card mb-2">
                        <div class="card-header announcement_header">
                            <div class="announcement_header">
                                <div class="announcement_img_container">
                                    <img src="{{ asset('assets/img/pilot.png') }}" alt="">
                                </div>
                                <div>
                                    <h5 class="mx-2 my-0">Juan Dela Cruz</h5>
                                </div>
                            </div>
                            <div class="edit_btn">
                                <i class="fa-solid fa-square-plus btn btn-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div id="enrolled_users_container"></div>

            @if (AAuth::user()->not_for_sp_fi())
                <div class="announce_form-controller mt-5 mb-3" style="cursor: pointer;" id="enroll_student_container">
                    <div class="google-classroom-announce announce_btn">
                        <div class="announce-text w-100">
                            <div class="d-flex align-items-center">
                                <div class="announcement_img_container">
                                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="leiaai logo">
                                </div>
                                <div>
                                    <h5 class=" mx-2 my-0">Enroll Student</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3 d-none my-3" id="enroll_student_form">
                    <input type="text" class="form-control" placeholder="Search Students"
                        aria-label="Insert Students" aria-describedby="basic-addon1">
                    <button type="button" class="btn btn-outline-danger" id="close_enroll_student_btn">Close</button>
                </div>
            @endif

            <div id="enrolled_student_container"></div>
        </div>
        {{-- grades --}}
        <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="grade">
            <p>Grade</p>
        </div>
    </div>
@endsection
