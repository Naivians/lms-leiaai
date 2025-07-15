@php
    $title = 'Lessons';
    $headerTitle = 'Feedback Form';
@endphp

@extends('layouts.app')

@section('header_title', $headerTitle)
@section('content')
    <form id="feedback_form">

        <!-- Instructional message -->
        <div class="alert alert-info" role="alert">
            You can send feedback or make requests about the system, features, or overall experience — just avoid personal
            matters.
        </div>

        <input type="hidden" name="feedback_content" id="feedback_content">

        <div id="toolbar" class="w-100">
            <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
            <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
            <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
            <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
            <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
        </div>

        <div id="editor" style="height: 400px; width: 100%;" class="mb-2"></div>

        <div class="mt-5 mb-3">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fa-solid fa-comment"></i> Submit Feedback
            </button>
        </div>

    </form>


@endsection

@section('js_imports')
    <script src="{{ asset('assets/js/lessons.js') }}"></script>
@endsection
