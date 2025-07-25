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
                    <input type="hidden" name="assessmnet_id" id="assessment_id" data-assessment-id="{{ $assessments->id }}">
                </div>

                @foreach ($questions as $question)
                    <div class="question">{{ $questions->currentPage() }}. {{ $question->q_name }}</div>
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
                    <input type="hidden" name="assessment_id" id="assessment_id"
                        data-assessment-id="{{ $assessments->id }}">

                    <div>{{ $questions->currentPage() }} of {{ $questions->total() }} Questions</div>

                    <div>
                        @if (!$questions->onFirstPage())
                            <a href="{{ $questions->previousPageUrl() }}" class="text-decoration-none">
                                <button class="next-btn" id="previous">Back</button>
                            </a>
                        @endif

                        @if ($questions->hasMorePages())
                            <a href="{{ $questions->nextPageUrl() }}" class="text-decoration-none">
                                <button class="next-btn" id="next">Next Que</button>
                            </a>
                        @else
                            <button class="next-btn" id="finish">Finish</button>
                        @endif
                    </div>
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
                        <div id='result_icon'>
                            <img src="{{ asset(Auth::user()->img) }}" alt="" style="width: 150px; height: auto;">
                        </div>
                        <p class="text-success mt-3 " id="result_description">Nice job, you passed</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2 my-5">
                        <div class="result_percentage  bg-light text-center d-flex align-items-center justify-content-center flex-column text-success rounded"
                            style="width: 200px; height: 200px;">
                            <p style="font-size: 40px" class="m-0"><span id="percentage">100%</span></p>
                            <p class="text-success status">Passed</p>
                        </div>
                        <div class="result_percentage  bg-light text-center d-flex align-items-center justify-content-center flex-column  rounded"
                            style="width: 200px; height: 200px;">
                            <p style="font-size: 40px" class="m-0 text-success" id="points"><span id="score">8</span>
                                / <span id="totals">10</span></p>
                            <p class="text-success status">Passed</p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" class="text-decoration-none">
                        <button type="button" class="btn btn-secondary" id="endBtn">End</button>
                    </a>
                    <a href="#" class="text-decoration-none" id="reviewQuiz">
                        <button type="button" class="btn btn-primary">Review Quiz</button>
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var total = $('#total').val();
        var pages = $('#pages').val();
        var finishBtn = $('#finish');
        let show = parseInt(localStorage.getItem('show'));

        $(document).ready(function() {

            const assessment_id = $('#assessment_id').data('assessment-id');

            if (parseInt(localStorage.getItem('show')) === 1) {
                showResults();
                disableElements();
            }

            const options = $('#options');

            let allAnswers = JSON.parse(localStorage.getItem('answers') || '{}');

            let storedAnswers = allAnswers[assessment_id] || [];

            storedAnswers.forEach(ans => {
                const selector = `.option[data-q_id="${ans.qid}"][data-choice-id="${ans.cid}"]`;
                $(selector).addClass('correct');
            });

            options.on('click', '.option', function() {
                const question_id = $(this).data('q_id');
                const choice_id = $(this).data('choice-id');

                options.find(`.option[data-q_id="${question_id}"]`).removeClass('correct');
                $(this).addClass('correct');

                allAnswers[assessment_id] = allAnswers[assessment_id] || [];
                const answers = allAnswers[assessment_id];

                const index = answers.findIndex(ans => ans.qid === question_id);
                if (index >= 0) {
                    answers[index].cid = choice_id;
                } else {
                    answers.push({
                        qid: question_id,
                        cid: choice_id
                    });
                }

                localStorage.setItem('answers', JSON.stringify(allAnswers));
            });
        });

        finishBtn.on('click', () => {
            finishBtn.prop('disabled', true)
            finishBtn.text('Calculating.....')
            finishBtn.addClass('btn btn-secondary')
            launchConfetti()
            SaveResults()
            localStorage.setItem('show', 1)
        })

        $('#endBtn').on('click', () => {
            localStorage.setItem('show', 1)
            localStorage.removeItem('answers')
            localStorage.removeItem('results')
            pre_loader()
            setTimeout(() => {
                window.location.href = `${localStorage.getItem('class_id')}`
            }, 1500);
        })

        $('#reviewQuiz').on('click', () => {

            let results = JSON.parse(localStorage.getItem("results"))
            if (results == null) {
                alert("No result found in localStorage.");
                return
            }

            pre_loader()
            setTimeout(() => {
                window.location.href = `/assessments/progress/${results.progress_id}`
                localStorage.setItem('show', 1)
                localStorage.removeItem('answers')
                localStorage.removeItem('results')
            }, 1500);
        })

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

        function disableElements() {
            finishBtn.prop('disabled', true)
            finishBtn.text('Calculating.....')
            finishBtn.addClass('btn btn-secondary')
        }

        function showResults() {
            let results = JSON.parse(localStorage.getItem("results"))
            if (results == null) {
                alert("No result found in localStorage.");
                return
            }

            $('#result_description').text(results.result_description)
            $('#percentage').text(`${results.percentage}%`)
            $('.status').text(results.status)
            $('#score').text(results.score)
            $('#totals').text(total)
            if (results.status == "Failed") {
                $('.status').removeClass('text-success')
                $('.status').addClass('text-danger')
                $('#result_description').removeClass('text-success')
                $('#result_description').addClass('text-danger')
                $('#percentage').removeClass('text-success')
                $('#percentage').addClass('text-danger')
                $('#points').removeClass('text-success')
                $('#points').addClass('text-danger')
            }

            $('#results').modal({
                backdrop: false
            }).modal('show');
        }

        function SaveResults() {
            $.ajax({
                url: "/assessments/save_assessment",
                type: "POST",
                data: {
                    answers: JSON.parse(localStorage.getItem('answers')),
                    assessment_id: $('#assessment_id').data('assessment-id'),
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function(response) {
                    if (!response.success) {
                        error_message(response.message);
                        return;
                    }
                    const result = {
                        result_description: response.statusText,
                        percentage: response.percentage,
                        status: response.status,
                        score: response.score,
                        totals: response.total,
                        progress_id: response.progress_id,
                    };

                    localStorage.setItem("results", JSON.stringify(result));
                    showResults()
                },
                error: function(error) {
                    alert(`error: ${error}`);
                },
            });
        }
    </script>
@endsection
