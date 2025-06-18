@php
    $title = 'Announcement';
@endphp

@extends('layouts.app')
@section('header_title', 'Create Lesson')
@section('content')

    <form id="lessons_form">
        <div class="mb-3">
            <label for="title" class="form-label text-secondary">Lesson Title</label>
            <input type="text" name="title" id="title" class="form-control border border-1 w-25" required>
        </div>
        <input type="hidden" name="lessons_content" id="lessons_content">
        <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">
        <div id="toolbar" class="w-100">
            <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
            <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
            <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
            <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
            <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
        </div>

        <div id="editor" style="height: 200px; width: 100%;" class="mb-2"></div>

        <div class="mt-3">
            <label for="attachment" class="form-label text-secondary">Attachments (<span class="fst-italic">pdf, video,
                    image</span>)</label>
            <input type="file" name="attachment" id="attachment" class="form-control"
                accept=".jpeg,.jpg,.png,.mp4,.pdf">
        </div>

        <div class="attachments my-4">
            <div class="attachment-preview d-flex flex-wrap gap-2 mt-2">
                <div class="card" style="width: 200px;">
                    <div class="card-body text-center">
                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="" style="width: 80px; height: 80px; object-fit: cover;">
                        <p class="card-text">Attachment Name</p>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-attachment"
                            onclick="removeAttachment(this)"><i class="fa-solid fa-trash"></i> Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 mb-3">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-book-open-reader"></i> Create
                Lesson</button>
            </button>
            <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="btn btn-outline-danger">Back</a>
        </div>
    </form>

@endsection

@section('js_imports')
    <script src="{{ asset('assets/js/lessons.js') }}"></script>
@endsection
