@php
    $title = 'Assessments';
@endphp

@extends('layouts.assessment')
@section('content')
    <div class= "intro_main_container">
        <div class="intro_container">
            <h1 class="text-center mb-5">Balance and Performance</h1>
            <ul class="mb-5" class="intro_content">
                <li class="fs-4">Total Item: <span><strong>{{ $assessment->total }}</strong></span></li>
                <li class="fs-4">Type: <span><strong>{{ ucfirst($assessment->type) }}</strong></span></li>
                <li class="fs-4">Duration: <span><strong>{{ $assessment->assessment_time }}</strong></span></li>
                <li class="fs-4">Date: <span><strong>{{ $assessment->assessment_date }}</strong></span></li>
            </ul>
            <div class="text-center d-flex gap-2 mx-4">
                <a href="{{ route('assessment.take', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}" class="btn btn-primary fs-4 mb-4">Start</a>
                <a href="{{ route('assessment.index') }}" class="btn btn-warning fs-4 mb-4">Back</a>
                <button class="btn btn-primary fs-4 mb-4" id="playSound"><i class="fa-solid fa-volume-high"></i></button>
                <button class="btn btn-primary fs-4 mb-4" id="pauseSound"><i class="fa-solid fa-volume-xmark"></i></button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var source = "{{ asset('assets/audio/plane_sound.mp3') }}";
        var audio = new Audio(source);
        audio.loop = true;
        audio.volume = 0.5;

        $('#playSound').click(function() {
            audio.play().catch(function(error) {
                console.log("Playback error:", error);
            });
        });
        $('#pauseSound').click(function() {
            audio.pause()
        });
    </script>
@endsection
