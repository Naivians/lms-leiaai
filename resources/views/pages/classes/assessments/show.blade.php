@php
    $title = 'Assessments';
@endphp

@extends('layouts.app')

@section('header_title', 'Show Assessment')

@section('content')



    <h1>{{ $assessment->title }}</h1>

    @foreach ($assessment->question as $question)
        <div class="mb-4">
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
