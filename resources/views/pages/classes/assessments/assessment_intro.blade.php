@php
    $title = 'Assessments ';
@endphp

@extends('layouts.assessment')
@section('content')
    <div class= "intro_main_container" style="width: 100%;">
        <div class="intro_container">
            <h1 class="text-center">{{ $assessment->name }}</h1>
            <ul class="mb-2 intro_content list-unstyled mb-0">
                <li class="fs-4">Total Item: <span><strong>{{ $assessment->total }}</strong></span></li>
                <li class="fs-4">Type: <span><strong>{{ ucfirst($assessment->type) }}</strong></span></li>
                <li class="fs-4">Duration: <span><strong>{{ $assessment->assessment_time }}</strong></span></li>
                <li class="fs-4">Date: <span><strong>{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('l, F j, Y') }}</strong></span></li>
            </ul>
            <div class="alert alert-warning my-4">
                <strong>Assessment Briefing:</strong>
                <ul class="mb-0 mt-2">
                    <li>Once the assessment sequence is initiated, previously displayed questions will no longer be
                        accessible.</li>
                    <li>Timekeeping commences immediately upon activation of the first question.</li>
                    <li>Items may be skipped and re-engaged later, provided remaining time permits.</li>
                    <li>All items should be addressed prior to expiration of the allotted time window.</li>
                    <li>Select <strong>"Submit"</strong> to transmit your final responses and terminate the session.</li>
                    <li>Upon submission, your performance log and assessment results will become available for review.</li>
                    <li>In the event of system irregularities or operational anomalies, notify your instructor or proctor
                        without delay.</li>
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
        localStorage.removeItem("exam_end_time_{{ $assessment->id }}"); // Clear old time
        localStorage.setItem('pages', 0)
        localStorage.setItem('show', 0);
        const class_id = "{{ route('class.stream', ['class_id' => Crypt::encrypt($assessment->class_id)]) }}";
        localStorage.setItem('class_id', class_id);
    </script>
@endsection
