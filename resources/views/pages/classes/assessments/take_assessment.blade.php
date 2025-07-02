@php
    $title = 'Assessments';
@endphp

@extends('layouts.assessment')
@section('content')
    @if ($assessments != null)
        <div class="quiz_outer_container">
            <div class="quiz-container mx-auto">
                <div class="quiz-header">
                    <h2>{{ $assessments->name }}</h2>
                    <div class="timer">Time Left: <span id="time">00</span></div>
                </div>

                @foreach ($questions as $question)
                    <div class="question">{{ $loop->iteration }}. {{ $question->q_name }}</div>
                    <div class="options" id="options">
                        @foreach ($question->choices as $choice)
                            <div class="option" data-choice-id="{{ $choice->id }}" data-q_id={{ $question->id }}>
                                {{ $choice->choices }}
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <div class="footer">

                    <input type="hidden" name="total" id="total" value="{{ $questions->total() }}">
                    <input type="hidden" name="pages" id="pages" value="{{ $questions->currentPage() }}">
                    <div>{{ $questions->currentPage() }} of {{ $questions->total() }} Questions</div>

                    @if ($questions->hasMorePages())
                        <a href="{{ $questions->nextPageUrl() }}">
                            <button class="next-btn" id="next">Next Que</button>
                        </a>
                    @else
                        <button class="next-btn" id="finish">Finish</button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade show" id="results" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Assessment Results</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="result_icon text-center">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 100px" id='result_icon'></i>
                        <p class="text-success mt-3" id="result_description">Nice job, you passed</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2 my-5">
                        <div class="result_percentage  bg-light text-center d-flex align-items-center justify-content-center flex-column text-success rounded"
                            style="width: 200px; height: 200px;">
                            <p style="font-size: 40px" class="m-0">100%</p>
                            <p class="text-success">Passed</p>
                        </div>
                        <div class="result_percentage  bg-light text-center d-flex align-items-center justify-content-center flex-column  rounded"
                            style="width: 200px; height: 200px;">
                            <p style="font-size: 40px" class="m-0"><span id="points">8</span> / <span
                                    id="total">10</span></p>
                            <p class="text-success">Passed</p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="back">End</button>
                    <button type="button" class="btn btn-primary">Review Quiz</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // window.addEventListener('beforeunload', function(e) {
        //     e.preventDefault();
        //     e.returnValue = '';
        // });
        $(document).ready(function() {
            const options = $('#options');
            options.on('click', '.option', function() {
                options.find('.option').removeClass('correct');
                $(this).addClass('correct');

                const choice_id = $(this).data('choice-id');
                const question_id = $(this).data('q_id');

                let answers = JSON.parse(localStorage.getItem('answers') || '[]');

                const index = answers.findIndex(ans => ans.qid === question_id);

                if (index >= 0) {
                    answers[index].cid = choice_id;
                } else {
                    answers.push({
                        qid: question_id,
                        cid: choice_id
                    });
                }

                localStorage.setItem('answers', JSON.stringify(answers));
                getAnswers();
            });

            let time = parseInt(localStorage.getItem('timer'));
            let origTime = parseInt(localStorage.getItem('origTime'));
            const timeDisplay = document.getElementById('time');
            timeDisplay.textContent = time.toString().padStart(2, '0');

            const timer = setInterval(() => {
                time--;
                timeDisplay.textContent = time.toString().padStart(2, '0');

                localStorage.setItem('timer', time);
                if (time <= 0) {
                    clearInterval(timer);
                    localStorage.setItem('timer', time);
                    if (pages == total) {
                        finishBtn.prop('disabled', true)
                        finishBtn.text('Calculating.....')
                        finishBtn.addClass('btn btn-secondary')
                        localStorage.setItem('timer', 0);
                        launchConfetti()
                        showResults()
                        return
                    }
                    $('#next').click();
                    localStorage.setItem('timer', origTime);
                }
            }, 1000);
        });

        function getAnswers() {
            const stored = localStorage.getItem('answers');
            if (!stored) return;
            const answers = JSON.parse(stored);
            return answers
        }

        $('#back').on('click', () => {
            localStorage.setItem('timer', 30);
            window.location.href = "/assessments"
        })


        function launchConfetti(duration = 5000) {
            const end = Date.now() + duration;

            (function frame() {
                // Random confetti burst
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

        var total = $('#total').val();
        var pages = $('#pages').val();
        var finishBtn = $('#finish');

        finishBtn.on('click', () => {
            localStorage.removeItem('answers')
            finishBtn.prop('disabled', true)
            finishBtn.text('Calculating.....')
            finishBtn.addClass('btn btn-secondary')
            showResults()
            launchConfetti()
            confetti({
                particleCount: 200,
                spread: 70,
                origin: {
                    y: 0.6
                }
            });

            setTimeout(() => {
                window.location.href = '/assessments'
            }, 5000)

        })

        function showResults() {

            $('#results').modal({
                backdrop: false
            }).modal('show');

            $('#results .modal-body').hide().fadeIn(500);

        }
    </script>
@endsection
