@php
    $title = 'Reset Password';
@endphp

@extends('layouts.auth')

@section('content')
    @include('partials.messages', ['title' => 'Validation Error'])

    <div class="container w-50 mt-5">
        <h1 class="bg-primary text-white p-3 mb-3">Reset Password</h1>

        <form action="{{ route('password.update') }}" method="POST" class="border border-1 px-3 pt-3 pb-5 rounded-3">
            @csrf

            {{-- Required hidden fields --}}
            <input type="hidden" name="token" value="{{ request('token') }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="*************" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="*************" required>
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="show_password">
                    <label class="form-check-label" for="show_password">Show Password</label>
                </div>
            </div>

            <div class="text-end">
                <input type="submit" value="Update" class="btn btn-primary">
            </div>
        </form>
    </div>

    <script>
        document.getElementById('show_password').addEventListener('change', function () {
            const pw = document.getElementById('password');
            const pwc = document.getElementById('password_confirmation');
            const type = this.checked ? 'text' : 'password';
            pw.type = type;
            pwc.type = type;
        });
    </script>
@endsection
