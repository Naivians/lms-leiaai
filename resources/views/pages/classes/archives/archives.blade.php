@php
    $title = 'Archives';
@endphp

@extends('layouts.app')

@section('header_title', 'Archives Management')

@section('content')

    @if (isset($archives) && count($archives) > 0)
        <div class="class_grid_container">
            @foreach ($archives as $class)
                <div class="card">
                    <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $class->class_name }} - {{ $class->course_name }}</h5>
                        <p class="card-text">{{ $class->class_description }}</p>
                    </div>
                    @if (Auth::user()->role === 4 || Auth::user()->role === 5)
                        <div class="card-footer">
                            <a href="#" class="deleteClassBtn btn btn-primary" data-bs-toggle="tooltip"
                                title="restore this class" data-id="{{ Crypt::encrypt($class->id) }}">
                                <i class="fa-solid fa-folder-plus"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif; color: #555;">
            <i class="fas fa-calendar-times fa-3x" style="color: #ff6b6b;"></i>
            <h2 style="margin-top: 20px;">No archive classes Available</h2>
        </div>
    @endif

@endsection
