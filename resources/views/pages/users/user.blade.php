@php
    $title = 'User';
@endphp

@extends('layouts.app')

@section('header_title', 'User Management')

@section('content')
    <table id="userTable" class="display w-100">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID Number</th>
                <th>Student Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Verification Staus</th>
                <th>Login Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection
