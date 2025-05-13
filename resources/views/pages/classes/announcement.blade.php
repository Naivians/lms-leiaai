@php
    $title = 'Announcement';
@endphp

@extends('layouts.app')
@section('header_title', 'Make Announcements')
@section('content')
    {{-- <div class="form-floating mb-2">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"></textarea>
                            <label for="floatingTextarea">Announce something to your class</label>
                        </div> --}}

    <!-- Toolbar -->
    <div id="toolbar" class="w-100">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-clean"></button>
    </div>
    <!-- Editor -->
    <div id="editor" style="height: 400px; width: 100%;" class="mb-2"></div>
    <a href="{{ route('class.stream') }}" class="btn btn-outline-danger">Back</a>
    <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-bullhorn"></i> Announce
    </button>
@endsection
