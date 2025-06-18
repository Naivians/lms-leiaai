@php
    $title = 'Announcement';
@endphp

@extends('layouts.app')
@section('header_title', 'Create Announcements')
@section('content')

    @if (!isset($announcement))
        <form id="announcement_form">
            <input type="hidden" name="announcement_content" id="announcement_content">
            <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">
            <div id="toolbar" class="w-100">
                <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
                <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
                <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
                <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
                <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
            </div>

            <div id="editor" style="height: 400px; width: 100%;" class="mb-2"></div>
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-bullhorn"></i> Announce
            </button>
            <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="btn btn-outline-danger">Back</a>
        </form>
    @else
        <form id="edit_announcement_form">
            <input type="hidden" name="edit_announcement_content" id="edit_announcement_content">
            <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">
            <input type="hidden" name="announcement_id" value="{{ $announcement->id ?? null }}">
            <div id="toolbar" class="w-100">
                <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
                <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
                <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
                <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
                <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
            </div>

            <div id="editor" style="height: 400px; width: 100%;" class="mb-2" >
                {!! $announcement->content !!}
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-bullhorn"></i> Update Announcement
            </button>
            <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="btn btn-outline-danger">Back</a>
        </form>
    @endif

@endsection
