@php
    $title = 'Login';
@endphp

@extends('layouts.auth')

@section('content')
    @include('partials.messages', ['title' => 'Login Error'])
    <div class="login-container">
        <div class="school-header text-center">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="School Logo" class="school-logo">
            <h2 class="school-name">Leading Edge International Aviation Academy, Inc.</h2>
        </div>

        <div class="login-header">
            <p>Please login to your account</p>
        </div>

        <form action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="login" placeholder="Id Number / Email" required
                    autocomplete="off">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required
                    autocomplete="off">
            </div>

            <button type="submit" class="btn-login">Login</button>

            <div class="login-links text-center">
                <a href="{{ route('auth.password.request') }}">Forgot Password</a> <br>
                <a href="{{ route('auth.Register') }}">No Account yet? Sign up here</a>
            </div>
        </form>
    </div>
@endsection
