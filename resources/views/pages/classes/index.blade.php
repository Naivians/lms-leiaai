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
                    <img src="{{ $class->file_path }}" class="card-img-top"
                        style="width: 100%; height: 100px; object-fit: cover;" alt="Class Image">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $class->class_name }}</h5>
                        <p class="card-text">{{ $class->class_description }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('class.stream', ['class_id' => Crypt::encrypt($class->id)]) }}"
                            class="btn btn-outline-primary">View Class</a>
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
