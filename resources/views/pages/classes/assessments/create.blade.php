@php
    $title = 'Assessments | Create';
@endphp

@extends('layouts.app')

@section('header_title', 'Create Assessments')

@section('content')
    <div class="container">

        @if (isset($assessments) && count($assessments) > 0)
            <form id="assessmentForm">
                <div class="mb-4">
                    <input type="text" name="name" id="name" class="form-control p-3 border-bottom" autocomplete="off"
                        placeholder="Assessment Title" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="class_id" class="form-label">Select Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="" selected disabled>.....</option>

                            <option value="a">sample</option>
                            <option value="a">sample</option>
                            <option value="a">sample</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="assessment_date" class="form-label">Assessment Date</label>
                        <input type="date" name="assessment_date" id="assessment_date" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Assessment Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="quiz">Quiz</option>
                            <option value="exam">Exam</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="assessment_time" class="form-label">Time Duration (mins)</label>
                        <input type="text" name="assessment_time" id="assessment_time" class="form-control"
                            autocomplete="off" placeholder="e.g. 30">
                        <small class="text-muted text-info"></small>
                    </div>


                </div>

                <div id="questions_container">
                    {{-- <div class="card mx-auto">
                        <div class="card-header bg-primary text-light">Which of the following documents must be carried on
                            board an aircraft during international flights?</div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="choices[]" id="radioDefault1">
                                <label class="form-check-label" for="radioDefault1">
                                    <input type="text" name="choices[]" id="choices[]" class="form-control" autocomplete="off">
                                </label>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <div class="input-group w-25 d-flex align-items-center">
                                <label for="correct" class="form-label text-secondary me-3 mt-2">Correct Answer</label>
                                <input type="text" name="correct" id="correct" class="form-control"
                                    placeholder="eg. A">
                            </div>
                            <i class="fa-solid fa-trash btn btn-outline-danger" data-bs-toggle="tooltip"
                                title="remove question"></i>
                        </div>
                    </div> --}}
                </div>

                <div class="mb-3 mt-5 d-flex align-items-center justify-content-end gap-1">
                    <input type="submit" value="Create" class="btn btn-primary">
                    @if ($class_id == null)
                        <a href="{{ route('assessment.index') }}" class="btn btn-outline-danger"><i
                                class="fa-solid fa-arrow-left-long"></i> Back</a>
                    @else
                        <a href="{{ route('class.stream', ['class_id' => $class_id ?? null]) }}"
                            class="btn btn-outline-danger"><i class="fa-solid fa-arrow-left-long"></i> Back</a>
                    @endif
                </div>
            </form>
        @else
            <div class="d-flex flex-column align-items-center">
                <i class="fas fa-exclamation-circle text-danger mb-3" style="font-size: 3rem;"></i>
                <h2 class="mb-2">No Classes Available</h2>
                <p class="text-muted">
                    You cannot create an assessment without any classes. Please add a class or contact support for help.
                </p>
                <a href="{{ route('class.index') }}" class="btn btn-primary">Go back</a>
            </div>
        @endif

    </div>
@endsection

@section('js_imports')
    <script src="{{ asset('assets/js/assessment.js') }}"></script>
@endsection
