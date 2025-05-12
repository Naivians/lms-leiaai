@php
    $title = 'Classes';
@endphp

@extends('layouts.app')

@section('header_title', 'Class Management')

@section('content')

    <div class="class_grid_container">
        <div class="card">
            <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title fw-bold">ATPL Class Batch - 2025</h5>
                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem, corporis!</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('class.stream') }}" class="btn btn-outline-primary">View Class</a>
            </div>
        </div>
        <div class="card">
            <img src="{{ asset('assets/img/leiaai_logo.png') }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title fw-bold">CPL Class Batch - 2025</h5>
                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem, corporis!</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('class.stream') }}" class="btn btn-outline-primary">View Class</a>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script></script>
@endsection
