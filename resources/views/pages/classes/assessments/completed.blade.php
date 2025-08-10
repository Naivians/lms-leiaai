@php
    $title = 'Assessments Results';
@endphp

@extends('layouts.assessment')

{{-- 'assessmentProgress', 'assessment' --}}

@section('content')
    <div class= "intro_main_container" style="height: 600px;">
        <div class="intro_container" >
            <div class="result_icon text-center">
                <div id='result_icon'>
                    <img src="{{ asset(Auth::user()->img) }}" alt="" style="width: 150px; height: auto;">
                </div>
                @if ($assessmentProgress->status == 'Passed')
                    <p class="text-success mt-3" id="result_description">Congratulations, you passed the assessment!</p>
                @else
                    <p class="text-danger mt-3" id="result_description">Unfortunately, you did not pass the assessment.</p>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-center gap-2 my-5">
                <div class="result_percentage  bg-light text-center d-flex align-items-center justify-content-center flex-column text-success rounded"
                    style="width: 200px; height: 200px;">
                    <p style="font-size: 40px" class="m-0"><span id="percentage">
                            @php
                                $percentage = ($assessmentProgress->score / $assessment->total) * 100;
                                $textStatus = '';
                                if ($percentage >= 75) {
                                    $textStatus = 'Passed';
                                } else {
                                    $textStatus = 'Failed';
                                }
                            @endphp
                            {{ $percentage }}%</span>
                        </span></p>
                    <p class="text-success status">{{ $textStatus }}</p>
                </div>
                <div class="result_percentage  bg-light text-center d-flex align-items-center justify-content-center flex-column  rounded"
                    style="width: 200px; height: 200px;">
                    <p style="font-size: 40px" class="m-0 text-success" id="points"><span
                            id="score">{{ $assessmentProgress->score }}</span>
                        / <span id="totals">{{ $assessment->total }}</span></p>
                    <p class="text-success status">{{ $textStatus }}</p>
                </div>
            </div>

            <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="text-decoration-none">
                <button type="button" class="btn btn-secondary" id="endBtn">End</button>
            </a>
            <a href="{{ route('assessment.view.progress', ['progress_id' => $assessmentProgress->id]) }}"
                class="text-decoration-none" id="reviewQuiz">
                <button type="button" class="btn btn-primary">Review Quiz</button>
            </a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        launchConfetti(5000);

        function launchConfetti(duration = 5000) {
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: {
                        x: 0
                    }
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: {
                        x: 1
                    }
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            })();
        }
    </script>
