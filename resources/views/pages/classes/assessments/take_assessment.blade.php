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
                                    <input type="hidden" name="cid" value="{{ $choice->id }}">
                                    {{-- <input type="radio" name="cid" value="{{ $choice->id }}" required> --}}
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
