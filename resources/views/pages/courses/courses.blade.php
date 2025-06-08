@php
    $title = 'Courses';
@endphp

@extends('layouts.app')

@section('header_title', 'Courses Management')

@section('content')
    <div class="card mb-3">
        <div class="card-body" id="">
            <div class="alert alert-warning">
                <strong>Note:</strong> If you want to <strong>edit</strong>, <strong>delete</strong>, or
                <strong>add</strong> a new course aside from those listed below, please contact the developer.
            </div>
            <h5 class="card-title" id="card-title">Add New Course</h5>
            <div class="addCourse">
                <select name="course_name" id="course_name" class="form-select w-25" onchange="AddCourse()">
                    <option value="" disabled selected>Select Course Code</option>
                    <option value="PPL">PPL</option>
                    <option value="CPL">CPL</option>
                    <option value="ATPL">ATPL</option>
                    <option value="ME">ME</option>
                    <option value="FIC">FIC</option>
                </select>
            </div>

            <div class="editCourse d-none">
                <form id="editCourse">
                    <div class="w-50 d-flex align-items-center justify-content-center gap-2">
                        <input type="hidden" name="course_id" id="course_id">
                        <input type="text" class="form-control" name="course_names" id="course_names" required
                            autocomplete="off">

                        <input type="text" class="form-control" name="course_description" id="course_description"
                            required autocomplete="off">
                        <div class="input-group">
                            <button class="btn btn-primary" type="button" onclick="EditCourse()">Update</button>
                            <button class="btn btn-outline-primary" onclick="backToCourses()" type="button">Back</button>
                        </div>
                    </div>
                </form>
            </div>

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
