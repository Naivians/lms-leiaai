@php
    $title = 'Login';
@endphp

@extends('layouts.auth')

@section('content')
    @include('partials.messages', ['title' => 'Login Error'])
    <div class="container mt-3">

        <div class=" border border-1 w-50 mx-auto">
            <h1 class="bg-primary p-3 text-white text-center">Register Account</h1>
            <div class="my-3 alert alert-warning d-none" id="errors">
                <ul class="px-3 m-0" id="errorList"></ul>
            </div>
            <form class="row g-3 p-3" id="registerForm">
                <div class="col-md-12">
                    <label for="id_number" class="form-label">ID Number <span class="text-warning"> (optional but
                            needed)</span></label>
                    <input type="text" class="form-control" id="id_number" name="id_number" placeholder="24-007">
                </div>

                <div class="col-md-12">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        placeholder="Last Name, First Name MI." required>
                </div>

                <div class="col-md-12">
                    <label for="contact" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="contact" name="contact"
                        placeholder="Always starts with country code with '+' sign eg. +63" maxlength="15"
                        oninput="validatePhoneInput(this)" required>

                </div>

                <div class="col-md-12">
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
                        <input type="checkbox" id="show_password"> Show Password
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
                    <a href="{{ route('login') }}" class="btn btn-outline-danger float-end">Back</a>
                    <button class="btn btn-primary float-end me-2" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
    {{-- <div class="bg-light">
    </div> --}}
@endsection
