@php
    $title = 'Assessments | Take';
@endphp

@extends('layouts.app')

@section('header_title', 'Take Assessment')

@section('content')

    <h1>{{ $assessment->name }}</h1>
    <h2>{{$assessment->assessment_time}}</h2>
    <h2>{{$assessment->assessment_date}}</h2>
    <h2>{{$assessment->type}}</h2>
    <h2>{{$assessment->total}}</h2>

    @foreach ($assessment->question as $question)
        <div class="my-3 bg-light p-3 rounded">
            <h4>{{ $question->q_name }}</h4>

            @foreach ($question->choices as $choice)
                <div>
                    <label>
                        <input type="radio" name="question_{{ $question->id }}" value="{{ $choice->id }}">
                        {{ $choice->choices }}
                    </label>

                    {{-- Optional: show correct answer key (if you want it visible) --}}
                    @if ($choice->answerKey)
                        <small class="text-success">(Correct Answer)</small>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
@endsection
