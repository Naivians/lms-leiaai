@php
    $title = 'User Management';
@endphp
@extends('layouts.app')

@section('header_title', (isset($users)) ? 'Update Users' :'Register Users')
@section('content')
    <div class="m-0 alert alert-warning d-none" id="errors">
        <ul class="px-3 m-0" id="errorList"></ul>
    </div>
    @if (!isset($users))
        <form class="row g-3 p-3" id="registerForm">
            <div class="col-md-6">
                <label for="id_number" class="form-label">ID Number <span class="text-warning"> (optional but
                        needed)</span></label>
                <input type="text" class="form-control" id="id_number" name="id_number" placeholder="24-007">
            </div>

            <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    placeholder="Last Name, First Name MI." required>
            </div>

            <div class="col-md-6">
                <label for="contact" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="contact" name="contact"
                    placeholder="Always starts with country code with '+' sign eg. +63" maxlength="15"
                    oninput="validatePhoneInput(this)" required>

            </div>

            <div class="col-md-6">
                <label for="validationCustom03" class="form-label">Email</label>
                <input type="email" class="form-control" id="validationCustom03" name="email"
                    placeholder="juandelacruz@gmail.com" required>
                <div class="invalid-feedback">
                    Email field is required
                </div>
            </div>

            <div class="col-md-6">
                <label for="validationCustom06" class="form-label">Password</label>
                <input type="password" class="form-control" id="validationCustom06" name="password"
                    placeholder="*************" required>
                <label class="mt-2" for="show_password">
                    <input type="checkbox" onclick="showPassword()"> Show Password
                </label>
            </div>

            <div class="col-md-6">
                <label for="validationCustom05" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="validationCustom05" name="password_confirmation"
                    placeholder="*************" required>
                <div id="passwordMatchMsg" style="color: red; margin-top: 5px; display: none;">
                    Passwords do not match.
                </div>
            </div>

            <div class="col-md-6">
                <label for="gender" class="form-label">Select Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option selected disabled value="">Choose...</option>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Rather not say</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="img" class="form-label">Choose Image</label>
                <input type="file" name="img" accept="image/png, image/jpeg" id="img" class="form-control">
            </div>
            <div class="col-md-12">
                <a href="{{ route('user.index') }}" class="btn btn-outline-danger float-end">Back</a>
                <button class="btn btn-primary float-end me-2" type="submit">Register</button>
            </div>
        </form>
    @else
        @if (Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 2)
            <div class="alert alert-warning">
                <p>Ask Registrar if you want to change the following:</p>
                <ol>
                    <li>Gender</li>
                    <li>ID number</li>
                    <li>Phone Number</li>
                    <li>Email Address</li>
                </ol>
            </div>
        @endif

        <form id="updateForm" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $users->id }}">
            <div class="d-flex align-items-start gap-1">
                <div class="card view_img_container p-3">
                    <img src="{{ asset($users->img) }}">
                    <input type="file" name="img" id="img" class="form-control mt-3">
                </div>
                <div class="view_content border border-1 w-100 p-2">
                    <div class="col-md-12">
                        <label for="id_number" class="form-label">ID Number <span class="text-warning small"> (optional
                                but
                                needed)</span></label>
                        <input type="text" class="form-control" id="id_number" name="id_number"
                            value="{{ $users->id_number }}"
                            {{ Auth::user()->role === 3 || Auth::user()->role === 4 || Auth::user()->role === 5 ? '' : 'disabled' }}>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="name" class="form-label text-secondary">Name</label>
                            <input type="text" name="name" id="name" class="form-control enable_input"
                                placeholder="John" value="{{ $users->name }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="contact" class="form-label text-secondary">Phone Number</label>
                            <input type="text" name="contact" id="contact" class="form-control enable_input"
                                 value="{{ $users->contact }}"
                                {{ Auth::user()->role === 3 || Auth::user()->role === 4 || Auth::user()->role === 5 ? '' : 'disabled' }}>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="email" class="form-label text-secondary">Email Address</label>
                            <input type="text" name="email" id="email" class="form-control enable_input"
                                placeholder="manasesjohn@gmail.com" value="{{ $users->email }}"
                                {{ Auth::user()->role === 3 || Auth::user()->role === 4 || Auth::user()->role === 5 ? '' : 'disabled' }}>
                        </div>

                        @if (Auth::user()->role === 3 || Auth::user()->role === 4 || Auth::user()->role === 5)
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Select Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option selected value="{{ $users->gender ?? '' }}">
                                        {{ $gender ?? 'Choose...' }}
                                    </option>
                                    <option value="0">Male</option>
                                    <option value="1">Female</option>
                                    <option value="2">Rather not say</option>
                                </select>
                            </div>

                            {{ $users->role }}

                            <div class="col-md-4">
                                <label for="validationCustom04" class="form-label">Select Role</label>
                                <select class="form-select" id="validationCustom04" name="role">
                                    {{-- <option selected value="{{ $users->role }}">{{ $roles }}
                                    </option> --}}
                                    <option value="0">Student</option>
                                    <option value="1">Flight Instructor</option>
                                    <option value="2">CGI</option>
                                    <option value="3">Registrar</option>
                                    <option value="4">Admin</option>
                                    <option value="5">Super Admin</option>
                                </select>
                            </div>
                        @else
                            <div class="mb-3 col-md-4">
                                <label for="gender" class="form-label text-secondary">Gender</label>
                                <input type="text" name="gender" id="gender" class="form-control enable_input"
                                    value="{{ $gender }}" disabled>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="role" class="form-label text-secondary">Role</label>
                                <input type="text" name="role" id="role" class="form-control enable_input"
                                    value="{{ $roles }}" disabled>
                            </div>
                        @endif



                    </div>
                    <div class="col-md-12 mt-3">
                        <button class="btn btn-primary" type="submit">{{ $users ? 'Update Info' : 'Register' }}</button>
                        @if (Auth::user()->role === 3 || Auth::user()->role === 4 || Auth::user()->role === 5)
                            <a href="{{ route('user.index') }}" class="btn btn-outline-danger">Back</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-danger">Back</a>
                        @endif
                    </div>
                </div>

            </div>
        </form>
    @endif

@endsection
