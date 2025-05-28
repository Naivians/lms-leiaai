@php
    $title = 'User Management';
@endphp
@extends('layouts.app')
@section('header_title', 'Register Users')
@section('content')

    <form class="row g-3" id="registerForm">

        <div class="col-md-6">
            <label for="fname" class="form-label">First name</label>
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Juan" required>
        </div>


        <div class="col-md-6">
            <label for="lname" class="form-label">Last name</label>
            <input type="text" class="form-control" id="lname" name="lname" placeholder="Manuel" required>
        </div>

        <div class="col-md-6">
            <label for="mname" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="mname" name="mname" placeholder="Marquez (optional)">
        </div>

        <div class="col-md-6">
            <label for="suffix" class="form-label">Suffix</label>
            <input type="text" class="form-control" id="suffix" name="suffix"
                placeholder="I, II, III, Jr. etc. (Optional)">
        </div>

        <div class="col-md-6">
            <label for="contact" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="contact" name="contact" placeholder="+639923237899"
                maxlength="13" oninput="validatePhoneInput(this)" required>
            <div class="invalid-feedback">
                Please insert a valid phone number.
            </div>
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
            <input type="password" class="form-control" id="validationCustom06" name="password" placeholder="*************"
                required>
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

        <div class="col-md-3">
            <label for="gender" class="form-label">Select Gender</label>
            <select class="form-select" id="gender" name="gender" required>
                <option selected disabled value="">Choose...</option>
                <option value="0">Male</option>
                <option value="1">Female</option>
                <option value="2">Rather not say</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="validationCustom04" class="form-label">Select Role</label>
            <select class="form-select" id="validationCustom04" name="role" required>
                <option selected disabled value="">Choose...</option>
                <option value="0">Student</option>
                <option value="1">Flight Instructor</option>
                <option value="2">CGI</option>
                <option value="3">Registrar</option>
                <option value="4">Admin</option>
                <option value="5">Super Admin</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="img" class="form-label">Choose Image</label>
            <input type="file" name="img" accept="image/png, image/jpeg" id="img" class="form-control">
        </div>

        <div class="col-md-12">
            <a href="{{ route('user.Home') }}" class="btn btn-outline-danger float-end">Back</a>
            <button class="btn btn-primary float-end me-2" type="submit">Register</button>
        </div>
    </form>
@endsection
