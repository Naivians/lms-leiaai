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
                <th>Student Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection
