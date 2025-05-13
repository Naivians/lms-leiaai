@php
    $title = 'Streams';
@endphp

@extends('layouts.app')

@section('content')
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

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="grade" data-bs-toggle="tab" data-bs-target="#tab4" type="button"
                role="tab" aria-controls="tab4" aria-selected="false">
                Grade
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade" id="tab1" role="tabpanel" aria-labelledby="streams">
            <a href="{{ route('class.announcement') }}" class="text-decoration-none">
                <div class="announce_form-controller my-3">
                    <div class="google-classroom-announce announce_btn">
                        <div class="announce-text w-100">
                            <div>
                                <div class="d-flex align-items-center">
                                    <div class="announcement_header">
                                        <div class="announcement_img_container">
                                            <img src="{{ asset('assets/img/student-male.png') }}" alt="">
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
                            <img src="{{ asset('assets/img/student-male.png') }}" alt="">
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
                            <img src="{{ asset('assets/img/student-male.png') }}" alt="">
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
        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="classwork">
            <p>Classwork</p>
        </div>
        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="people">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Teacher</h2>
                <div class="rounded-circle add-teacher-btn" data-bs-toggle="modal">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </div>
            <hr class="my-2">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/leiaai_logo.png') }}" alt="" class="rounded-circle me-2"
                    style="width: 50px; height: 50px;">
                <h5 class="m-0">John Doe</h5>
            </div>
            <hr>
        </div>
        <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="grade">
            <p>Grade</p>
        </div>
    </div>
@endsection
