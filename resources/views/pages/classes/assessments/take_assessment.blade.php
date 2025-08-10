@php
    $title = 'Assessments';
@endphp

@extends('layouts.assessment')
@section('content')

    @if ($assessment)
        <div class="quiz_outer_container">
            <div class="quiz-container mx-auto">
                <div class="quiz-header">
                    <h2>{{ $assessment->name }}</h2>
                </div>
                @foreach ($questions as $question)
                    <form action="{{ route('assessment.answer', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}"
                        method="POST">
                        @csrf
                        <div class="question">
                            {{ $questions->currentPage() }}. {{ $question->q_name }}
                        </div>

                        <div class="options" id="options">
                            @foreach ($question->choices as $choice)
                                <label class="option" data-choice-id="{{ $choice->id }}" data-q_id={{ $question->id }}>

                                    <input type="hidden" name="qid" value="{{ $question->id }}">
                                    <input type="radio" name="cid" value="{{ $choice->id }}" required >
                                    {{ $choice->choices }}
                                </label>
                            @endforeach
                        </div>

                        <div class="footer">
                            <div>
                                {{ $questions->currentPage() }} of {{ $questions->total() }} Questions
                            </div>

                            <a href="#" class="btn btn-outline-warning">Skip</a>
                            <button type="submit" class="next-btn">
                                @if ($questions->hasMorePages())
                                    Next Question
                                @else
                                    Finish
                                @endif
                            </button>
                        </div>
                    </form>
                @endforeach
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
        $(document).ready(function() {
            const options = $('#options');

            options.on('click', '.option', function() {
                const question_id = $(this).data('q_id');
                const choice_id = $(this).data('choice-id');
                options.find(`.option[data-q_id="${question_id}"]`).removeClass('correct');
                $(this).addClass('correct');
            });

        });
    </script>
@endsection
