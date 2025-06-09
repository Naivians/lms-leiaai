@php
    $title = 'Classes';
@endphp

@extends('layouts.app')

@section('header_title', 'Class Management')

@section('content')
    @if (isset($classes))
        <div class="class_grid_container">
            @foreach ($classes as $class)
                {{ dd($class->file_path) }}
                <div class="card">
                    <img src="{{$class->file_path}}" class="card-img-top" alt="...">
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
    <h2>No class availbale</h2>
    @endif



@endsection

@section('scripts')
    <script>

    </script>
@endsection
