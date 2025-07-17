@php
    $title = 'Streams';
@endphp

@extends('layouts.app')
@section('styles')
    <style>
        .table-fixed thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #fff;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            z-index: 11;
            background-color: #fff;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .name-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-sort {
            min-width: 200px;
        }
    </style>
@endsection

@section('class_title', 'Welcome to ' . $class_name ?? '')
@section('content')


      <!-- Nav tabs -->
  <ul class="nav nav-tabs" id="classTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="Streams-tab" data-bs-toggle="tab" data-bs-target="#Streams" type="button" role="tab">
        Streams
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="Classwork-tab" data-bs-toggle="tab" data-bs-target="#Classwork" type="button" role="tab">
        Classwork
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="People-tab" data-bs-toggle="tab" data-bs-target="#People" type="button" role="tab">
        People
      </button>
    </li>
      @can('fi_only')
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="grade" data-bs-toggle="tab" data-bs-target="#tab4" type="button"
                    role="tab" aria-controls="tab4" aria-selected="false">
                    Grades
                </button>
            </li>
        @endcan
  </ul>

  <!-- Tab panes -->
  <div class="tab-content pt-3">
    <div class="tab-pane fade show active" id="Streams" role="tabpanel">
      @can('not_for_sp')
                <a href="{{ route('announcement.index', ['class_id' => $class_id ?? null, 'announcement_id' => 0]) }}"
                    class="text-decoration-none">
                    <div class="announce_form-controller my-3">
                        <div class="google-classroom-announce announce_btn">
                            <div class="announce-text w-100">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <div class="announcement_header">
                                            <div class="announcement_img_container">
                                                <img src="{{ asset('assets/img/pilot.png') }}" alt="">
                                            </div>
                                        </div>
                                        <div>
                                            <p class=" mx-2 my-0">Announce something to your class</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endcan


            {{-- Announcements --}}

            @if (isset($announcements) && $announcements->count() > 0)
                @foreach ($announcements as $announcement)
                    <div class="card my-3">
                        <div class="card-header announcement_header">
                            <div class="announcement_header">
                                <div class="announcement_img_container">
                                    <img src="{{ $announcement->user->img }}" alt="">
                                </div>
                                <div>
                                    <h5 class="mx-2 my-0">{{ $announcement->user->name }}</h5>
                                    <small class="mx-2">{{ $announcement->user->role_label }}</small>
                                </div>
                            </div>
                            @can('not_for_sp')
                                <div class="edit_btn">
                                    <a href="{{ route('announcement.index', ['class_id' => Crypt::encrypt($announcement->class_id), 'announcement_id' => $announcement->id]) }}"
                                        class="btn btn-outline-warning text-decoration-none">
                                        <i class="fa-solid fa-pen-to-square ">
                                        </i>
                                    </a>
                                    <i class="fa-solid fa-trash btn btn-outline-danger"
                                        onclick='delete_announcement({{ $announcement->id }})'></i>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            {!! $announcement->content !!}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center text-muted my-5">
                    <i class="fas fa-bullhorn fa-3x mb-3"></i>
                    <h4>No Announcements Available</h4>
                    <p class="text-secondary">Stay tuned — announcements will appear here once posted.</p>
                </div>
            @endif
    </div>
    <div class="tab-pane fade" id="Classwork" role="tabpanel">
      @can('admin_lvl1')
                <div class="dropdown">
                    <button class="btn btn-primary" type="button" data-bs-toggle="dropdown">
                        Create Classwork
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                                href="{{ route('lesson.index', ['class_id' => $class_id, 'lesson_id' => 0]) }}">Lessons</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="{{ route('assessment.create', ['class_id' => $class_id]) }}">Assessments</a>
                        </li>
                    </ul>
                </div>
            @endcan

            <div>
                <h2 class="text-secondary pb-2 border-bottom mt-3">Lessons</h2>
                <div class="lessons_stream_container">

                    @if ($lessons->isNotEmpty())
                        @foreach ($lessons as $index => $lesson)
                            @php
                                $accodrionParent = 'accordionParent' . $index;
                            @endphp
                            <div class="accordion mb-2" id="{{ $accodrionParent }}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button
                                            class="accordion-button collapsed d-flex justify-content-between align-items-center"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#{{ $lesson->id }}"
                                            aria-expanded="false" aria-controls="{{ $lesson->id }}">

                                            <span>{{ $lesson->title }}</span>

                                            <div class="ms-auto d-flex gap-2">
                                                <a href="{{ route('lesson.index', ['class_id' => $class_id ?? null, 'lesson_id' => $lesson->id]) }}"
                                                    class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="view">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                @can('cgi_only')
                                                    <span class="btn btn-sm btn-danger"
                                                        onclick="deleteLesson({{ $lesson->id }})" data-bs-toggle="tooltip"
                                                        title="delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </span>
                                                @endcan
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="{{ $lesson->id }}" class="accordion-collapse collapse"
                                        data-bs-parent="#{{ $accodrionParent }}">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                {!! $lesson->description !!}

                                                <div class="d-flex flex-wrap gap-2 mt-2">
                                                    @foreach ($lesson->materials as $attachment)
                                                        <div class="card m-2" style="width: 200px;">
                                                            <div class="card-body text-center">
                                                                @if ($attachment->extension == 'pdf')
                                                                    <a href="{{ route('lesson.pdf', ['pdf_url' => base64_encode($attachment->path)]) }}"
                                                                        target="_blank">
                                                                        <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                                            alt="PDF Icon"
                                                                            style="width: 80px; height: 80px; object-fit: contain;">
                                                                    </a>
                                                                    <div class="mt-2 small text-muted">
                                                                        {{ $attachment->filename ?? 'PDF File' }}
                                                                    </div>
                                                                @elseif ($attachment->extension == 'mp4')
                                                                    <a href="{{ asset($attachment->path) }}"
                                                                        target="_blank">
                                                                        <video muted autoplay
                                                                            src="{{ asset($attachment->path) }}"
                                                                            style="width: 100%; height: 100px; object-fit: cover;"
                                                                            muted>
                                                                        </video>
                                                                    </a>
                                                                    <div class="mt-2 small text-muted">
                                                                        {{ $attachment->filename ?? 'Video File' }}
                                                                    </div>
                                                                @elseif (in_array($attachment->extension, ['jpg', 'jpeg', 'png']))
                                                                    <a href="{{ asset($attachment->path) }}"
                                                                        target="_blank">
                                                                        <img src="{{ asset($attachment->path) }}"
                                                                            alt="{{ $attachment->filename }}"
                                                                            style="width: 100%; height: 100px; object-fit: cover;">
                                                                    </a>
                                                                    <div class="mt-2 small text-muted">
                                                                        {{ $attachment->filename ?? 'Image' }}
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 2rem; color: #6c757d;">
                            <i class="fas fa-book-open fa-3x" style="margin-bottom: 1rem;"></i>
                            <h3 style="font-weight: 500;">No Lessons Available</h3>
                            <p>Please check back later or contact your instructor.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="text-secondary pb-2 border-bottom mt-3">Quizes | Exams</h2>
                @if ($assessments && count($assessments) > 0)
                    <div class="lessons_stream_container">
                        <table class="table table-striped">
                            <thead>
                                <th class="bg-secondary text-light">Title</th>
                                <th class="bg-secondary text-light">Type</th>
                                <th class="bg-secondary text-light">Total</th>
                                <th class="bg-secondary text-light">Time Duration</th>
                                <th class="bg-secondary text-light">Assessment Date</th>
                                <th class="bg-secondary text-light">Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($assessments as $assessment)
                                    <tr>
                                        <td>{{ $assessment->name }}</td>
                                        <td>{{ $assessment->type }}</td>
                                        <td>{{ $assessment->total }}</td>
                                        <td>{{ $assessment->assessment_time }}</td>
                                        <td>{{ $assessment->assessment_date }}</td>
                                        @if (Gate::allows('sp_only'))
                                            <td>
                                                <a href="{{ route('assessment.show', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}"
                                                    class="btn btn-outline-primary">Take
                                                    {{ ucfirst($assessment->type) }}</a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('assessment.edit', ['assessment_id' => Crypt::encrypt($assessment->id)]) }}"
                                                    class="btn btn-outline-primary">View
                                                    {{ ucfirst($assessment->type) }}</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: #6c757d;">
                        <i class="fas fa-file-alt fa-3x" style="margin-bottom: 1rem;"></i>
                        <h3 style="font-weight: 500;">No Quizzes or Exams Available</h3>
                        <p>Please check back later or wait for your instructor to assign them.</p>
                    </div>
                @endif
            </div>
    </div>
    <div class="tab-pane fade" id="People" role="tabpanel">
       @can('not_for_sp_fi')
                <div class="announce_form-controller my-3" style="cursor: pointer;" id="enroll_fi_container"
                    data-toggle-form="enroll_fi">
                    <div class="google-classroom-announce announce_btn">
                        <div class="announce-text w-100">
                            <div class="d-flex align-items-center">
                                <div class="announcement_header">
                                    <div class="announcement_img_container">
                                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="leiaai logo">
                                    </div>
                                </div>
                                <div>
                                    <h5 class=" mx-2 my-0">Enroll FI / CGI</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group my-3 d-none" id="enroll_fi_form">
                    <input type="search" class="form-control" id="search_fi" placeholder="Search FI or CG"
                        aria-label="Enroll FI or CGI" aria-describedby="basic-addon1">
                    <button type="button" class="btn btn-outline-danger" id="close_enroll_fi_btn"
                        data-close-form="enroll_fi">Close</button>
                </div>

                <div id="fi_search_results"></div>
            @endcan
            <h5>Flight Instructor | CGI</h5>
            <div id="enrolled_fi_cgi_container" class="my-4"></div>

            @can('admin_lvl1')
                <div class="announce_form-controller mt-5 mb-3" style="cursor: pointer;" id="enroll_student_container"
                    data-toggle-form="enroll_student">
                    <div class="google-classroom-announce announce_btn">
                        <div class="announce-text w-100">
                            <div class="d-flex align-items-center">
                                <div class="announcement_img_container">
                                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="leiaai logo">
                                </div>
                                <div>
                                    <h5 class=" mx-2 my-0">Enroll Student</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mt-5 mb-3 d-none" id="enroll_student_form">
                    <input type="search" class="form-control" placeholder="Search Students" aria-label="Enroll Students"
                        aria-describedby="basic-addon1" id="search_student">
                    <button type="button" class="btn btn-outline-danger" id="close_enroll_student_btn"
                        data-close-form="enroll_student">Close</button>
                </div>

                <div id="students_search_results"></div>
            @endcan
            <h5 class="mt-5">{{ Gate::allows('sp_only') ? 'Classmates' : 'Students' }}</h5>
            <div id="enrolled_student_container" class="my-4"></div>
    </div>
   @can('fi_only')
            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="grade">
                @if ($assessments && count($assessments) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-fixed">
                            <thead>
                                <tr>
                                    <th class="sticky-col" style="width: 50px;">
                                        Students Name
                                    </th>

                                    @foreach ($assessments as $assessment)
                                        @if ($assessment->type == 'exam')
                                            <th style="width: 200px;">
                                                <div>
                                                    <div>
                                                        <strong>{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('M j, Y') }}</strong>
                                                    </div>
                                                    <div>{{ $assessment->name }} ({{ ucfirst($assessment->type) }})</div>
                                                    <div class="text-muted small">out of {{ $assessment->total }}</div>
                                                </div>
                                            </th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $students = collect();
                                    foreach ($assessments as $assessment) {
                                        foreach ($assessment->progress as $progress) {
                                            $students->push($progress->user);
                                        }
                                    }
                                    $students = $students->unique('id')->sortBy('name')->values();
                                @endphp

                                @foreach ($students as $student)
                                    @if ($student != null)
                                        <tr>
                                            <td class="sticky-col">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($student->img) }}" alt="Avatar"
                                                        class="avatar me-2">
                                                    <span>{{ $student->name }}</span>
                                                </div>
                                            </td>

                                            @foreach ($assessments as $assessment)
                                                @php
                                                    $progressList = $assessment->progress->where(
                                                        'user_id',
                                                        $student->id,
                                                    );
                                                @endphp

                                                <td>
                                                    @if ($progressList->isNotEmpty())
                                                        @foreach ($progressList as $i => $progress)

                                                            @if ($assessment->type == 'exam')
                                                                <div class="mb-1">
                                                                    <label for=""
                                                                        class="form-label text-muted small"><strong>{{ \Carbon\Carbon::parse($progress->created_at)->format('M j, Y H:i:s a') }}</strong></label>

                                                                    <div
                                                                        class="align-items-center d-flex justify-content-between border border-1 rounded p-2">
                                                                        <p class="m-0">
                                                                            {{ $progress->score == 0 ? 0 : $progress->score}} points
                                                                        </p>
                                                                        @if ($progress->status == 'passed')
                                                                            <span class="badge bg-success">
                                                                                {{ round(($progress->score / $progress->total) * 100, 2) }}%
                                                                            </span>
                                                                        @else
                                                                            <div class="me-2">
                                                                                <span class="badge bg-danger">
                                                                                    {{ $progress->status }}
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted my-5">
                        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                        <h4>No Assessments Available</h4>
                        <p class="text-secondary">Grades will appear here once assessments are completed.</p>
                    </div>
                @endif

            </div>
        @endcan
  </div>
</div>
@endsection
