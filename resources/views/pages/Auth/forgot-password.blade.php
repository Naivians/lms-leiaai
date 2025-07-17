@php
    $title = 'Forgot Password';
@endphp

@extends('layouts.auth')

@section('content')
    @include('partials.messages', ['title' => 'Validation Error'])

    <div class="container mt-5">
        <h1 class="bg-primary text-white p-3 mb-3">Forgot Your Password?</h1>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="border border-1 px-3 pt-3 pb-5 rounded-3">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="johndoe@example.com"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
                <a href="{{ route('login') }}" class="btn btn-outline-danger">Back</a>
            </div>
        </form>
    </div>
@endsection
