@php
    $title = 'Assessments | Progress';
@endphp

@extends('layouts.app')

@section('header_title', 'Assessments Progress Management')

@section('content')

    <table id="progress" class="display w-100">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Assessment Name</th>
                <th>Type</th>
                <th>Total</th>
                <th>Score</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
@endsection
