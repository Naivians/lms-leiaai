@php
    $title = 'Assessments ';
@endphp

@extends('layouts.assessment')
@section('content')
    <div class="assessment_info_container">
        @php
            $percentage =
                $assessment_progress->total > 0 ? round(($assessment_progress->score / $assessment_progress->total) * 100, 2) : 0;
        @endphp

        <h5>Name: <span><strong>{{ $assessment->name }}</strong></span></h5>
        <h5>Type: <span><strong>{{ ucfirst($assessment->type) }}</strong></span></h5>
        <h5>Total: <span><strong>{{ $assessment->total }}</strong></span></h5>
        <h5>Score: <span><strong>{{ $assessment_progress->score }}</strong></span></h5>
        <h5>Percentage: <span><strong>{{ $percentage }}</strong></span></h5>
        <h5>Status: <span><strong>{{ $assessment_progress->status }}</strong></span></h5>

        <div class="my-4">
            <a href="{{ route('assessment.show.progress') }}" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i>
                Back</a>
        </div>
    </div>
    <div class="view_progress_container">
        @foreach ($questions as $index => $question)
            <div class="card w-75 mx-auto my-3">
                <div class="card-header">
                    <h5 class="m-0">{{ $index + 1 }}. {{ $question->q_name }}</h5>
                </div>

                <div class="card-body">
                    @foreach ($question->choices as $choice)
                        @php
                            $isCorrect = $choice->answer_key && $choice->answer_key->choice_id == $choice->id;
                        @endphp

                        <div class="options">
                            <div class="option my-2 {{ $isCorrect ? 'correctAnswer' : 'incorrect' }}"
                                style="cursor:default;">
                                {{ $choice->choices }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-footer">
                    <label class="form-label">Your Answer</label>

                    @php
                        $userAnswer = $progress_detail->firstWhere('qid', $question->id);
                        $selectedChoice = $question->choices->firstWhere('id', $userAnswer?->cid);
                        $isCorrect = $selectedChoice && $selectedChoice->answer_key && $selectedChoice->answer_key->choice_id == $selectedChoice->id;
                    @endphp

                    @if ($selectedChoice)
                        <div class="options">
                            <div class="option user-answer d-flex align-items-center justify-content-between" style="cursor:default;">
                                {{ $selectedChoice->choices }}
                                <i class="fa-solid {{ $isCorrect ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger' }} ms-2"></i>
                            </div>
                        </div>
                    @else
                        <p><em>No answer provided</em></p>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
@endsection
