@php
    $title = 'Instructor';
@endphp

@extends('layouts.app')

@section('header_title', 'Flight Instructor Management')

@section('content')
    <table id="classwork_fi_table" class="display w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Suffix</th>
                <th>Role</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection
