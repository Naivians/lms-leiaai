@php
    $title = 'Courses';
@endphp

@extends('layouts.app')

@section('header_title', 'Courses Management')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Note:</strong> If you want to <strong>edit</strong>, <strong>delete</strong>, or <strong>add</strong> a new course aside from those listed below, please contact the developer.
            </div>
            <h5 class="card-title">Add New Course</h5>
            <select name="course_name" id="course_name" class="form-select" onchange="AddCourse()">
                <option value="" disabled selected>Select Course Code</option>
                <option value="PPL">PPL</option>
                <option value="CPL">CPL</option>
                <option value="ATPL">ATPL</option>
                <option value="ME">ME</option>
                <option value="FIC">FIC</option>
            </select>
        </div>
    </div>


    <table id="courseTable" class="display w-100">
        <thead>

            <tr>
                <th>Course Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection
