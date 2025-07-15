@php
    $title = 'Feedback';
@endphp

@extends('layouts.app')

@section('header_title', 'User Feedback Management')

@section('content')

    <div class="container my-5">


        <h3 class="text-center mb-4">What Our Students Say</h3>
        <p class="text-center mb-5">Here are some of the feedback we have received from our students.</p>

        @if ($feedbacks->isEmpty())
            <div class="alert alert-info text-center">
                No feedback available.
            </div>
        @else
            <div class="row g-4">
                @foreach ($feedbacks as $feedback)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100 border-0 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <i class="fa-solid fa-quote-left text-primary fs-3"></i>
                                    <div class="mt-2 text-muted" style="font-style: italic;">
                                        {!! $feedback->feedback !!}
                                    </div>
                                </div>
                                <div class="mt-auto d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $feedback->anonymous_name ?? 'Anonymous' }}</h6>
                                        <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
