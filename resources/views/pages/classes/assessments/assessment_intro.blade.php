@php
    $title = 'Assessments ';
@endphp

@extends('layouts.assessment')
@section('content')
    <div class= "intro_main_container">
        <div class="intro_container">
            <h1 class="text-center mb-5">{{ $assessment->name }}</h1>
            <ul class="mb-2" class="intro_content">
                <li class="fs-4">Total Item: <span><strong>{{ $assessment->total }}</strong></span></li>
                <li class="fs-4">Type: <span><strong>{{ ucfirst($assessment->type) }}</strong></span></li>
                <li class="fs-4">Duration: <span><strong>{{ $assessment->assessment_time }}</strong></span></li>
                <li class="fs-4">Date: <span><strong>{{ $assessment->assessment_date }}</strong></span></li>
            </ul>
            <div class="alert alert-warning my-4">
                <strong>Important Instructions:</strong>
                <ul class="mb-0 mt-2">
                    <li>Once you begin the assessment, you will not be able to return to previous questions.</li>
                </ul>
            </div>
            <div class="text-center d-flex gap-2">
                <a href="{{ route('assessment.take', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}"
                    class="btn btn-primary fs-4 mb-4">Start</a>

                <a href="{{ Gate::allows('sp_fi_only') ? route('class.stream', ['class_id' => Crypt::encrypt($assessment->class_id)]) : route('assessment.index') }}"
                    class="btn btn-warning fs-4 mb-4">Back</a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        localStorage.setItem('show', 0);
        const class_id = "{{ route('class.stream', ['class_id' => Crypt::encrypt($assessment->class_id)]) }}";
        localStorage.setItem('class_id', class_id);
    </script>
@endsection
