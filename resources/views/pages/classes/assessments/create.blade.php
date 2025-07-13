@php
    $title = 'Assessments | Create';
@endphp

@extends('layouts.app')

@section('header_title', 'Create Assessments')

@section('content')
    <div class="container">
        @if (isset($classes) && count($classes) > 0)
            <form id="assessmentForms">
                <div class="mb-4">
                    <input type="text" name="name" id="name" class="form-control p-3 border-bottom" autocomplete="off"
                        placeholder="Assessment Title" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="class_id" class="form-label text-secondary">Select Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="" selected disabled> Choose....</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="assessment_date" class="form-label text-secondary">Assessment Date</label>
                        <input type="date" name="assessment_date" id="assessment_date" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label text-secondary">Assessment Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="quiz">Quiz</option>
                            <option value="exam">Exam</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="" class="form-label text-secondary">Time Duration</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="hrs" class="form-label text-secondary">Hour(s)</label>
                                <select name="hrs" id="hrs" class="form-select">
                                    <option value="00">00</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="hrs" class="form-label text-secondary">Minutes</label>
                                <select name="minutes" id="minutes" class="form-select">
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

                        <select name="total" id="total" class="form-select">
                            <option value="0" selected>0</option>
                            @for ($i = 1; $i <= 120; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div id="questions_container">

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
