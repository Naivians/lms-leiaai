@php
    $title = 'Streams';
@endphp

@extends('layouts.app')

@section('content')
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="streams" data-bs-toggle="tab" data-bs-target="#tab1" type="button"
                role="tab" aria-controls="tab1" aria-selected="true">
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
        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="streams">

            <div class="announce_form-controller my-3" style="cursor: pointer" data-bs-toggle="modal"
                data-bs-target="#announcementForm">
                <div class="google-classroom-announce announce_btn">
                    <div class="announce-text w-100">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <div>
                                    <img src="{{ asset('assets/img/leiaai_logo.png') }}" alt=""
                                        class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                </div>
                                <h5 class="m-0">Announce something to your class</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="announce_form-controller my-3">
                <div class="google-classroom-announce">
                    <div class="announce-text w-100">

                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <img src="{{ asset('assets/img/leiaai_logo.png') }}" alt=""
                                    class="rounded-circle me-2" style="width: 50px; height: 50px;">
                                <div>
                                    <h5 class="m-0">John Doe</h5>
                                    <p class="m-0">May 20, 2025</p>
                                </div>
                            </div>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatibus.</p>
                    </div>
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

@section('scripts')
    <script>
        var quill = new Quill('#editor', {
            modules: {
                toolbar: '#toolbar'
            },
            theme: 'snow'
        });

        let announce_btn = $('.announce_btn');
        let announce_form_controller = $('.announce_form_container');
        announce_btn.on('click', function() {
            announce_form_controller.toggleClass('d-none');
        });
    </script>
@endsection
