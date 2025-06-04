@php
    $title = 'Login';
@endphp

@extends('layouts.auth')

@section('content')
    @include('partials.messages', ['title' => "Login Error"])

    <div class="login_container">
        <div class="video_container" style="background-color: #000">
            <video src="{{ asset('assets/video/video_bg.mp4') }}" width="100%" height="100%" autoplay muted loop></video>
        </div>
        <div class="login_form_container d-flex flex-column align-items-center justify-content-center">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" class="img-fluid rounded-circle"
                    style="width: 80px; height: 80px;">
                <h4 class="mt-3">Welcome Back</h4>
                <p class="text-muted small">Please login to your account</p>
            </div>

            <form action="{{ route('auth.login') }}" method="POST" class="w-75">
                @csrf
                <label for="" class="form-label text-secondary">Id Number / Email</label>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" name="login" placeholder="Username" aria-label="Username"
                        required autocomplete="off">
                </div>
                <label for="" class="form-label text-secondary">Password</label>
                <div class="mb-3 input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Password" aria-label="Password"
                        required autocomplete="off">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill">Login</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <p class="small text-muted">No Account yet? <a href="{{ route('auth.Register') }}" class="small text-muted">Sign up here</a></p>
                <a href="#" class=" small text-muted">Forgot password?</a>
            </div>
        </div>
    @endsection
