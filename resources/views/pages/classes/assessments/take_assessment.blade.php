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
                            <button class="next-btn">Next Que</button>
                        </a>
                    @else
                        <button class="next-btn" id="finish">Finish</button>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
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
        });

        function getAnswers() {
            const stored = localStorage.getItem('answers');
            if (!stored) return;

            const answers = JSON.parse(stored);
            console.log('All answers:', answers);
        }

        var total = $('#total').val();
        var pages = $('#pages').val();
        var finishBtn = $('#finish');

        finishBtn.on('click', () => {
            localStorage.removeItem('answers')
            window.location.href = '/assessments'

        })

        let time = 5;
        const timeDisplay = document.getElementById('time');
        const timer = setInterval(() => {
            time--;
            timeDisplay.textContent = time.toString().padStart(2, '0');
            if (time <= 0) {
                clearInterval(timer);
                document.querySelector('.next-btn').click();
            }
        }, 1000);
    </script>
@endsection
