@php
    $title = 'Assessments';
@endphp

@extends('layouts.app')

@section('header_title', 'Assessments Management')

@section('content')

    <table id="assessments" class="display w-100">
        <thead>
            <tr>
                <th>Title</th>
                <th>Course</th>
                <th>Type</th>
                <th>total</th>
                <th>date</th>
                <th>duration</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection
