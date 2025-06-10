@php
    $title = 'Classes';
@endphp

@extends('layouts.app')

@section('header_title', 'Class Management')

@section('content')

    @if (isset($classes))

        <div class="class_grid_container">
            @foreach ($classes as $class)
                @if ($class->active)
                    <div class="card">
                        <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $class->class_name }} - {{ $class->course_name }}</h5>
                            <p class="card-text">{{ $class->class_description }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('class.stream', ['class_id' => Crypt::encrypt($class->id)]) }}"
                                data-bs-toggle="tooltip" title="View this class" class="btn btn-outline-primary"><i
                                    class="fa-solid fa-eye"></i></a>

                            <a href="#" data-bs-toggle="tooltip" title="Edit this class"
                                data-id="{{ Crypt::encrypt($class->id) }}" data-class_name="{{ $class->class_name }}"
                                class="btn btn-outline-warning editClassBtn"><i class="fa-solid fa-pen-to-square"></i></a>

                            <a href="#" class="deleteClassBtn btn btn-outline-danger" data-bs-toggle="tooltip"
                                title="Archive this class" data-id="{{ Crypt::encrypt($class->id) }}">
                                <i class="fa-solid fa-box-archive"></i>
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif; color: #555;">
            <i class="fas fa-calendar-times fa-3x" style="color: #ff6b6b;"></i>
            <h2 style="margin-top: 20px;">No Classes Available</h2>
            <p>Please check back later or contact support for more information.</p>
        </div>
    @endif



@endsection

@section('scripts')
    <script></script>
@endsection
