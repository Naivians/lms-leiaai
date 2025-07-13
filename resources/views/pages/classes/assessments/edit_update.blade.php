@php
    $title = 'Assessments | Create';
    $restriction = Gate::allows('fi_only') ? isset($assessments) && $assessments != null ? 'Assessment: ' . $assessments->name : 'View Assessment' : 'Edit | Update Assessments'
@endphp

@extends('layouts.app')

@section('header_title', $restriction)

@section('content')
    <div class="container">
        @if (isset($assessments) && $assessments != null)
            @can('fi_only')
                <div class="alert alert-secondary text-center my-4 py-4 rounded shadow-sm">
                    <h5 class="mb-1">You cannot edit this assessment</h5>
                    <p class="mb-0 text-muted">If you have any questions, please contact the registrar.</p>
                </div>
            @endcan
            <form id="editAssessmentForms">
                <div class="mb-4">
                    <input type="text" name="name" id="name" class="form-control p-3 border-bottom"
                        autocomplete="off" placeholder="Assessment Title" required value="{{ $assessments->name }}"
                        {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                </div>

                <input type="hidden" name="assessment_id" value="{{ $assessments->id }}">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="class_id" class="form-label text-secondary">Select Class</label>
                        <select name="class_id" id="class_id" class="form-select" required
                            {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                            <option value="{{ $assessments->class->id }}" selected class="text-primary">
                                {{ $assessments->class->class_name }}</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="assessment_date" class="form-label text-secondary">Assessment Date</label>
                        <input type="date" name="assessment_date" id="assessment_date" class="form-control" required
                            value="{{ $assessments->assessment_date }}" {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label text-secondary">Assessment Type</label>
                        <select name="type" id="type" class="form-select" required
                            {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                            <option value="{{ $assessments->type }}" class="text-primary">
                                {{ ucfirst($assessments->type) }}
                            </option>
                            <option value="quiz">Quiz</option>
                            <option value="exam">Exam</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="" class="form-label text-secondary">Time Duration</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="hrs" class="form-label text-secondary">Hour(s)</label>
                                <select name="hrs" id="hrs" class="form-select"
                                    {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                                    <option value="{{ $time['hours'] }}" selected class="text-primary">
                                        {{ $time['hours'] }}
                                    </option>
                                    <option value="00">00</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="hrs" class="form-label text-secondary">Minutes</label>
                                <select name="minutes" id="minutes" class="form-select"
                                    {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                                    <option value="{{ $time['minutes'] }}" selected class="text-primary">
                                        {{ $time['minutes'] }}</option>
                                    <option value="00">00</option>
                                    @for ($i = 1; $i < 60; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 " style="margin-top: 31px">
                        <label for="total" class="form-label text-secondary">Total Questions</label>
                        <input type="text" name="total" id="total" class="form-control" disabled
                            value="{{ $assessments->total }}">
                    </div>
                    <div class="col-md-4 mb-3 " style="margin-top: 31px">
                        <label for="total" class="form-label text-secondary">Would you like to publish it?</label>
                        <select name="is_published" id="is_published" class="form-select"
                            {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                            <option value="{{ $assessments->is_published }}" selected class="text-primary">
                                {{ $assessments->is_published ? 'Yes' : 'No' }}</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                @foreach ($assessments->question as $index => $questions)
                    <div class="card mx-auto my-3">
                        <div class="card-header bg-light text-light">
                            <input type="hidden" name="question_id[]" value="{{ $questions->id }}">
                            <input type="text" name="question[]" id="question_{{ $index }}" class="form-control"
                                placeholder="Question here" value="{{ $questions->q_name }}"
                                {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                        </div>

                        <div class="card-body">
                            @php $options = range('A', 'Z'); @endphp

                            @foreach ($questions->choices as $c_index => $choices)
                                <input type="hidden" name="choices_id_{{ $index }}[]"
                                    value="{{ $choices->id }}">
                                <div class="input-group mb-3">
                                    <button class="btn btn-secondary" disabled>
                                        {{ $options[$c_index] ?? '' }}
                                    </button>
                                    <input type="text" name="choices_{{ $index }}[]"
                                        id="choices_{{ $choices->id }}" class="form-control"
                                        value="{{ $choices->choices }}" {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                                </div>
                            @endforeach

                            @foreach ($questions->choices as $choices)
                                @foreach ($choices->answer_keys as $answers)
                                    <input type="hidden" name="correct_id[]" value="{{ $answers->id }}">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <div class="input-group w-75 d-flex align-items-center">
                                            <label for="correct_{{ $index }}"
                                                class="form-label text-secondary me-3 mt-2">Correct</label>
                                            <input type="text" name="correct_{{ $index }}"
                                                id="correct_{{ $answers->id }}" class="form-control"
                                                placeholder="e.g. A" value="{{ $answers->answer ?? 'no answer' }}"
                                                {{ Gate::allows('fi_only') ? 'disabled' : '' }}>
                                        </div>
                                        @can('admin_lvl1')
                                            <i class="fa-solid fa-trash btn btn-outline-danger" title="Remove question"
                                                onclick="removeQuestions({{ $questions->id }}, {{ $assessments->id }})"></i>
                                        @endcan
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach


                @can('admin_lvl1')
                    <div class="mb-3 mt-5 d-flex align-items-center justify-content-end gap-1">
                        <input type="submit" value="Update Assessments" class="btn btn-primary">
                    </div>
                @endcan
                <a href="{{ route('assessment.index') }}" class="btn btn-outline-danger"><i
                        class="fa-solid fa-arrow-left-long"></i> Back</a>
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
