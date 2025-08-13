@php
    $title = 'Assessments';
@endphp

@extends('layouts.assessment')
@section('content')

    @php
        $timeString = strtolower($assessment->assessment_time); // e.g., "1 hr and 30 mins"

        $hours = 0;
        $minutes = 0;

        // Extract hours (handles hr, hrs, hour, hours)
        if (preg_match('/(\d+)\s*(hour|hours|hr|hrs)/', $timeString, $matches)) {
            $hours = (int) $matches[1];
        }

        // Extract minutes (handles min, mins, minute, minutes)
        if (preg_match('/(\d+)\s*(minute|minutes|min|mins)/', $timeString, $matches)) {
            $minutes = (int) $matches[1];
        }

        $totalMinutes = $hours * 60 + $minutes;

        // Now calculate end time
        $endTime = now()->addMinutes($totalMinutes);
    @endphp
    <div id="exam-timer"
        class="position-fixed top-0 end-0 m-4 px-3 py-2 rounded shadow-lg fw-bold bg-dark text-success text-center d-flex align-items-center justify-content-center"
        style="font-size: 3rem; font-family: monospace; width: 300px; height: 100px; ">
        00:00:00
    </div>

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
                        <input type="hidden" name="qid" value="{{ $question->id }}">
                        <input type="hidden" name="page" value="{{ $questions->currentPage() }}">

                        <div class="question">
                            {{ $questions->currentPage() }}. {{ $question->q_name }}
                        </div>

                        {{-- <div class="options" id="options">
                            @foreach ($question->choices as $choice)
                                <label class="option" data-choice-id="{{ $choice->id }}" data-q_id={{ $question->id }}>
                                    <input type="hidden" name="qid" value="{{ $question->id }}">
                                    <input type="hidden" name="cid" value="{{ $choice->id }}">
                                    {{ $choice->choices }}
                                </label>
                            @endforeach
                        </div> --}}

                        <div class="options" id="options">
                            <input type="hidden" name="qid" value="{{ $question->id }}">

                            @foreach ($question->choices as $choice)
                                <label class="option" data-choice-id="{{ $choice->id }}" data-q_id="{{ $question->id }}">
                                    <input type="radio" name="cid" value="{{ $choice->id }}">
                                    {{ $choice->choices }}
                                </label>
                            @endforeach
                        </div>

                        <div class="footer d-flex justify-content-between align-items-center mt-3">
                            <div>
                                {{ $questions->currentPage() }} of {{ $questions->total() }} Questions
                            </div>

                            <div class="d-flex gap-2">
                                {{-- Skip --}}

                                @if ($questions->hasMorePages())
                                    <button type="submit" name="action" value="skip" class="btn btn-warning">
                                        Skip
                                    </button>
                                @endif
                                {{-- <button type="submit" name="action" value="skip" class="btn btn-warning">
                                    Skip
                                </button> --}}

                                {{-- Next / Finish --}}
                                <button type="submit" name="action"
                                    value="{{ $questions->hasMorePages() ? 'next' : 'finish' }}" class="btn btn-primary next-btn">
                                    @if ($questions->hasMorePages())
                                        Next Question
                                    @else
                                        Finish
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                @endforeach

                <div class="mt-4 p-3 border rounded bg-light">
                    <h6>Unanswered / Skipped Questions</h6>

                    <div class="d-flex flex-wrap gap-2">
                        @if (!empty($skippedPages))
                            @foreach ($skippedPages as $pageNum)
                                <a href="{{ route('assessment.take', ['assessment_id' => Crypt::encrypt($assessment->id), 'page' => $pageNum]) }}"
                                    class="btn btn-sm btn-warning">
                                    {{ $pageNum }}
                                </a>
                            @endforeach
                        @else
                            <div class="small text-muted">No skipped questions yet.</div>
                        @endif
                    </div>

                    <div class="mt-2 small">
                        <span class="badge bg-warning text-dark">Skipped (redeemable)</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        (function() {
            const endTimeFromServer = "{{ $endTime->timestamp }}";
            const timerKey = "exam_end_time_{{ $assessment->id }}";

            if (!localStorage.getItem(timerKey)) {
                localStorage.setItem(timerKey, endTimeFromServer);
            }

            const endTime = parseInt(localStorage.getItem(timerKey), 10) * 1000;
            const timerElement = document.getElementById('exam-timer');

            function updateTimer() {
                const now = new Date().getTime();
                let distance = endTime - now;

                if (distance < 0) {
                    timerElement.innerHTML = "TIME UP!";
                    localStorage.removeItem(timerKey); // clear stored time for retake
                    window.location.href =
                        "{{ route('assessment.complete', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}";
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.innerHTML =
                    String(hours).padStart(2, '0') + ":" +
                    String(minutes).padStart(2, '0') + ":" +
                    String(seconds).padStart(2, '0');
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        })();

        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Optional: disable going back entirely
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function() {
            history.pushState(null, null, location.href);
        });

        $(document).ready(function() {
            const options = $('#options');
            const submitBtn = $('.next-btn'); // adjust selector if needed

            // Disable the button initially
            submitBtn.prop('disabled', true);

            options.on('click', '.option', function() {
                const question_id = $(this).data('q_id');
                const choice_id = $(this).data('choice-id');

                // Remove selection from all options of the same question
                options.find(`.option[data-q_id="${question_id}"]`).removeClass('correct');

                // Mark the clicked option
                $(this).addClass('correct');

                // Enable the Next/Finish button
                submitBtn.prop('disabled', false);
            });
        });
    </script>
@endsection
