@php
    $title = 'Assessments';
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
                    <li>Each question is timed individually. You will have <strong>30 seconds</strong> to answer each item.
                    </li>
                    <li>If no response is selected within the allotted time, the system will automatically proceed to the
                        next question.</li>
                </ul>
            </div>
            <div class="text-center d-flex gap-2">
                <a href="{{ route('assessment.take', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}"
                    class="btn btn-primary fs-4 mb-4">Start</a>
                <a href="{{ route('assessment.index') }}" class="btn btn-warning fs-4 mb-4">Back</a>
                <button class="btn btn-primary fs-4 mb-4" id="playSound"><i class="fa-solid fa-volume-high"></i></button>
                <button class="btn btn-primary fs-4 mb-4" id="pauseSound"><i class="fa-solid fa-volume-xmark"></i></button>
                <input type="text" name="" id="timer" style="height: 50px;" class="form-control"
                    placeholder="adjust timer (default 30s)">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let seconds = 60;
        let minutes = 1 * 60;
        let hours = (minutes / 1) * 60;

        let countdown = setInterval(() => {
            console.log(`Time left: ${minutes}s`);
            minutes--;

            if (minutes < 0) {
                clearInterval(countdown);
                console.log("Time's up!");
            }
        }, 1000); // Runs every 1000ms (1 second)

        $(document).ready(() => {

            $('#timer').on('input', function() {
                let val = $(this).val();
                timer = val !== '' ? parseInt(val) : 30;
                localStorage.setItem('timer', timer)
                localStorage.setItem('origTime', timer)
            });
        })

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
