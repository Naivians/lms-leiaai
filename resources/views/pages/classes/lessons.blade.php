@php
    $title = 'Lessons';
@endphp

@extends('layouts.app')
@section('header_title', 'Create Lesson')
@section('content')

    @if ($lessons != null)
        <form id="lessons_form">
            <div class="mb-3">
                <label for="title" class="form-label text-secondary">Lesson Title</label>
                <input type="text" name="title" id="title" class="form-control border border-1 w-25" required
                    value="{{ $lessons->title ?? null }}">
            </div>
            <input type="hidden" name="lessons_content" id="lessons_content">
            <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">

            <div id="toolbar" class="w-100">
                <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
                <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
                <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
                <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
                <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
            </div>
            <div id="editor" style="height: 200px; width: 100%;" class="mb-2">
                {!! $lessons->description !!}
            </div>

            <div class="mt-3">
                <label for="attachment" class="form-label text-secondary">Attachments (<span class="fst-italic">pdf,
                        video,
                        image</span>)</label>
                <input type="file" name="attachment" id="attachment" class="form-control"
                    accept=".jpeg,.jpg,.png,.mp4,.pdf">
            </div>

            @if (count($materials) > 0)
                <h5 class="text-secondary mb-3 mt-5">Currently uploaded files</h5>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach ($materials as $material)
                        <div class="card m-2" style="width: 200px;">
                            <div class="card-body text-center">

                                @if ($material->extension == 'pdf')
                                    <a href="{{ asset($material->path) }}" target="_blank">
                                        <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon"
                                            style="width: 80px; height: 80px; object-fit: contain;">
                                    </a>
                                    <div class="mt-2 small text-muted">{{ $material->filename ?? 'PDF File' }}</div>
                                @elseif ($material->extension == 'mp4')

                                    <video src="{{ asset($material->path) }}"
                                        style="width: 100%; height: 100px; object-fit: cover;" muted controls></video>
                                    <div class="mt-2 small text-muted">{{ $material->filename ?? 'Video File' }}</div>

                                @elseif (in_array($material->extension, ['jpg', 'jpeg', 'png']))

                                    <img src="{{ asset($material->path) }}" alt="{{ $material->filename }}"
                                        style="width: 100%; height: 100px; object-fit: cover;">
                                    <div class="mt-2 small text-muted">{{ $material->filename ?? 'Image' }}</div>

                                @endif

                            </div>

                            <div class="card-footer text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-attachment"
                                    onclick="remove_to_db({{ $material->id }})">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <div class="alert alert-secondary text-center my-4 py-4 rounded shadow-sm">
                    <h5 class="mb-1">No materials uploaded</h5>
                    <p class="mb-0 text-muted">You haven’t added any files for this lesson yet.</p>
                </div>
            @endif
            <div class="attachments my-4">
                <h5 class="text-secondary mb-3 mt-5">Newly uploaded files</h5>
                <div class="attachment-preview d-flex flex-wrap gap-2 mt-2">

                </div>
            </div>
            <div class="mt-5 mb-3">
                <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-book-open-reader"></i>
                    Create
                    Lesson</button>
                </button>
                <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="btn btn-outline-danger">Back</a>
            </div>
        </form>
    @else
        <form id="lessons_form">
            <div class="mb-3">
                <label for="title" class="form-label text-secondary">Lesson Title</label>
                <input type="text" name="title" id="title" class="form-control border border-1 w-25" required>
            </div>
            <input type="hidden" name="lessons_content" id="lessons_content">
            <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">
            <div id="toolbar" class="w-100">
                <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
                <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
                <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
                <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
                <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
            </div>

            <div id="editor" style="height: 200px; width: 100%;" class="mb-2"></div>

            <div class="mt-3">
                <label for="attachment" class="form-label text-secondary">Attachments (<span class="fst-italic">pdf,
                        video,
                        image</span>)</label>
                <input type="file" name="attachment" id="attachment" class="form-control"
                    accept=".jpeg,.jpg,.png,.mp4,.pdf">
            </div>

            <div class="attachments my-4">
                <div class="attachment-preview d-flex flex-wrap gap-2 mt-2">
                    <div class="card" style="width: 200px;">
                        <div class="card-body text-center">
                            <img src="{{ asset('assets/img/logo.jpg') }}" alt=""
                                style="width: 80px; height: 80px; object-fit: cover;">
                            <p class="card-text">Attachment Name</p>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-attachment"
                                onclick="removeAttachment(this)"><i class="fa-solid fa-trash"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 mb-3">
                <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-book-open-reader"></i>
                    Create
                    Lesson</button>
                </button>
                <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="btn btn-outline-danger">Back</a>
            </div>
        </form>

    @endif


@endsection

@section('js_imports')
    <script src="{{ asset('assets/js/lessons.js') }}"></script>
@endsection
