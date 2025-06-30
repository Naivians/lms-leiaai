@php
    $title = 'Assessments';
@endphp

@extends('layouts.assessment')
@section('content')
    <div class="quiz_outer_container">
        <div class="quiz-container mx-auto">
            <div class="quiz-header">
                <h2>Awesome Quiz Application</h2>
                <div class="timer">Time Left: <span id="time">08</span></div>
            </div>

            <div class="question">1. What does HTML stand for?</div>
            <div class="options">
                <div class="option">Hyper Text Preprocessor</div>
                <div class="option">Hyper Text Markup Language</div>
                <div class="option">Hyper Text Multiple Language</div>
                <div class="option">Hyper Tool Multi Language</div>
            </div>

            <div class="footer">
                <div>1 of 5 Questions</div>
                <button class="next-btn">Next Que</button>
            </div>
        </div>
    </div>
@endsection
