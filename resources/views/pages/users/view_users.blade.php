@php
    $title = 'User Detailes';
@endphp

@extends('layouts.app')

@section('header_title', 'User Information')

@section('content')
    <div class="card mb-5">
        <div class="card-body">
            <form class="userEditForm">
                <div class="d-flex align-items-start gap-1">
                    <div class="card">
                        <img src="{{ asset('assets/img/sample.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <input type="file" name="user_img" id="user_img" class="form-control enable_input" disabled>
                        </div>
                    </div>
                    <div class="view_content border border-1 w-100 p-2">
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="fname" class="form-label text-secondary">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control enable_input" disabled placeholder="John"
                                    value="John">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="lname" class="form-label text-secondary">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control enable_input" disabled placeholder="Doe"
                                    value="Doe">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="mname" class="form-label text-secondary">Middle Name</label>
                                <input type="text" name="mname" id="mname" class="form-control enable_input" disabled
                                    placeholder="Manases" value="Manases">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="suffix" class="form-label text-secondary">Extension Name</label>
                                <input type="text" name="suffix" id="suffix" class="form-control enable_input" disabled
                                    placeholder="Jr, I, II, III, etc.. " value="Jr">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="email" class="form-label text-secondary">Email Address</label>
                                <input type="text" name="email" id="email" class="form-control enable_input" disabled
                                    placeholder="manasesjohn@gmail.com" value="manasesjohn@gmail.com">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="float-end">
                    <button type="submit" class="btn btn-outline-primary" id="user_update_btn">Update</button>
                    <button type="button" class="btn btn-outline-primary" id="edit_info">Edit</button>
                </div>
            </form>
        </div>
    </div>


    <div class="card my-2">
        <div class="card-header d-flex align-items-center">
            <i class="fa-solid fa-graduation-cap fs-1 me-2"></i>
            <h2 class="m-0">Class Enrolled</h2>
        </div>
    </div>
    <div class="class_grid_container mb-5">
        <div class="card">
            <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title fw-bold">ATPL Class Batch - 2025</h5>
                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem, corporis!</p>
            </div>
        </div>
        <div class="card">
            <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title fw-bold">CPL Class Batch - 2025</h5>
                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem, corporis!</p>
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-header d-flex align-items-center">
            <i class="fa-solid fa-bookmark fs-1 me-2"></i>
            <h2 class="m-0">Assessment Progress</h2>
        </div>
    </div>
    <table id="view_user_progress" class="display w-100">
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
@endsection
