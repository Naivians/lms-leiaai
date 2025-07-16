@php
    $title = 'Feedback';
@endphp

@extends('layouts.app')

@section('header_title', 'User Feedback Management')

@section('content')
    <style>
        .feedback-content > p, .feedback-content > ol, .feedback-content > ul {
            margin: 0;
        }
    </style>
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
                        <div class="card shadow-sm border-1 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <i class="fa-solid fa-quote-left text-primary fs-3"></i>
                                    <div class="mt-2 text-muted m-0 feedback-content">
                                        {!! $feedback->feedback !!}
                                    </div>
                                </div>
                                <div class="mt-auto d-flex align-items-center">
                                    <div>
                                        @can('admin_lvl3')
                                            <h6 class="mb-0 fw-bold">{{ $feedback->user_id ?? 'Anonymous' }}</h6>
                                        @endcan
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
