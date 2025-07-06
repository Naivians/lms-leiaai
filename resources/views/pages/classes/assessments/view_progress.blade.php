@php
    $title = 'Assessments ';
@endphp

@extends('layouts.assessment')
@section('content')
    {{-- <div class= "intro_main_container">
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

                <a href="{{ Gate::allows('sp_fi_only') ? route('class.index') : route('assessment.index') }}"
                    class="btn btn-warning fs-4 mb-4">Back</a>
            </div>
        </div>
    </div> --}}
    <div class="assessment_info_container">
        {{-- <ul style="list-style: none">
            <li>Assessment Name: <span><strong>{{ $assessment->name }}</strong></span></li>
            <li>Type: <span><strong>{{ ucfirst($assessment->type) }}</strong></span></li>
            <li>Total: <span><strong>{{ $assessment->total }}</strong></span></li>
            <li>Score: <span><strong>{{ $assessment_progress->score }}</strong></span></li>
            <li>Status: <span><strong>{{ $assessment_progress->status }}</strong></span></li>
        </ul> --}}

        <h5>Name: <span><strong>{{ $assessment->name }}</strong></span></h5>
        <h5>Type: <span><strong>{{ ucfirst($assessment->type) }}</strong></span></h5>
        <h5>Total: <span><strong>{{ $assessment->total }}</strong></span></h5>
        <h5>Score: <span><strong>{{ $assessment_progress->score }}</strong></span></h5>
        <h5>Status: <span><strong>{{ $assessment_progress->status }}</strong></span></h5>

        <div class="my-4">
            <a href="{{route('assessment.show.progress')}}" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Back</a>
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
                            <div class="option my-2 {{ $isCorrect ? 'correct' : 'incorrect' }}" style="cursor:default;">
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
                    @endphp

                    @if ($selectedChoice)
                        <div class="options">
                            <div class="option user-answer" style="cursor:default;">
                                {{ $selectedChoice->choices }}
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
