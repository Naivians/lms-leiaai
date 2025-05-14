@php
    $title = 'User Management';
@endphp
@extends('layouts.app')
@section('header_title', 'Register Users')
@section('content')


    <form action="" id="addForm">
        @csrf

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="fname" placeholder="First Name" required autocomplete="off">
            <label for="fname">First Name <span class="text-danger">*</span></label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="lname" placeholder="Last Name" required autocomplete="off">
            <label for="lname">Last Name <span class="text-danger">*</span></label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="ext_name" placeholder="Extention Name (if available)">
            <label for="ext_name">Ext. Name (if available)</label>
        </div>

        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required autocomplete="off">
            <label for="floatingInput">Email address <span class="text-danger">*</span></label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" placeholder="Password" required autocomplete="off">
            <label for="floatingInput">Password <span class="text-danger">*</span></label>
        </div>

        <div class="mt-3">
            <label for="" >Select Role <span class="text-danger">*</span></label>
            <select class="form-select mb-3" required autocomplete="off">
                <option value="0">Students</option>
                <option value="1">CGI</option>
                <option value="2">Admin</option>
                {{-- for devs only --}}
                <option value="3">Super Admin</option>
            </select>
        </div>

        <a href="{{ route('user.Home') }}" class="btn btn-outline-danger">Back</a>
        <input type="submit" value="Register" class="btn btn-primary">
    </form>


@endsection
