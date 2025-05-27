@php
    $title = 'User Management';
@endphp
@extends('layouts.app')
@section('header_title', 'Register Users')
@section('content')

    <form class="row g-3 needs-validation" novalidate>

        <div class="col-md-6">
            <label for="validationCustom01" class="form-label">First name</label>
            <input type="text" class="form-control" id="validationCustom01" name="fname" placeholder="Juan" required>
            <div class="invalid-feedback">
                First Name field is required
            </div>
        </div>

        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Last name</label>
            <input type="text" class="form-control" id="validationCustom02" name="lname" placeholder="Manuel" required>
            <div class="invalid-feedback">
                Last Name field is required
            </div>
        </div>

        <div class="col-md-6">
            <label for="mname" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="mname" name="mname" placeholder="Marquez (optional)">
        </div>

        <div class="col-md-6">
            <label for="suffix" class="form-label">Entension Name</label>
            <input type="text" class="form-control" id="suffix" name="suffix" placeholder="I, II, III, Jr. etc. (Optional)">
        </div>

        <div class="col-md-6">
            <label for="validationCustom03" class="form-label">Last name</label>
            <input type="email" class="form-control" id="validationCustom03" name="lname" placeholder="juandelacruz@gmail.com" required>
            <div class="invalid-feedback">
                Email field is required
            </div>
        </div>


        <div class="col-md-6">
            <label for="validationCustom04" class="form-label">Select Role</label>
            <select class="form-select" id="validationCustom04" required>
                <option selected disabled value="">Choose...</option>
                <option value="0">Student</option>
                <option value="1">Flight Instructor</option>
                <option value="2">CGI</option>
                <option value="3">Registrar</option>
                <option value="4">Super Admin</option>
            </select>
            <div class="invalid-feedback">
                Please select a valid role.
            </div>
        </div>

        <div class="col-md-6">
            <label for="img" class="form-label">Select Image</label>
            <input type="file" name="img" id="img" class="form-control">
        </div>

        <div class="col-md-12">
            <a href="{{ route('user.Home') }}" class="btn btn-outline-danger float-end">Back</a>
            <button class="btn btn-primary float-end me-2" type="submit">Register</button>
        </div>
    </form>


@endsection
