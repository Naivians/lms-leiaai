@php
    $title = 'Classes';
@endphp

@extends('layouts.app')

@section('header_title', 'Class Management')

@section('content')

    @if (isset($classes) && count($classes) > 0)

        <div class="class_grid_container">
            @foreach ($classes as $class)
                <div class="card">
                    <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="Class Image">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $class->class_name }} - {{ $class->course_name }}</h5>
                        <p class="card-text">{{ $class->class_description }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('class.stream', ['class_id' => Crypt::encrypt($class->id)]) }}"
                            class="btn btn-outline-primary"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('class.stream', ['class_id' => Crypt::encrypt($class->id)]) }}"
                            class="btn btn-outline-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">
            <h4 class="mb-0">No classes are currently available.</h4>
            <p class="mb-0">Please check back later or contact the <strong>registrar</strong> for more information.</p>
        </div>
    @endif



@endsection

@section('scripts')
    <script></script>
@endsection
