@php
    $title = 'Announcement';
@endphp

@extends('layouts.app')
@section('header_title', 'Create Announcements')
@section('content')

    @if (!isset($announcement))
        <form id="announcement_form">
            <input type="hidden" name="announcement_content" id="announcement_content">
            <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">

            <div id="toolbar" class="w-100">
                <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
                <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
                <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
                <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
                <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
            </div>

            <div id="editor" style="height: 400px; width: 100%;" class="mb-2"></div>
            {{-- tag classes --}}
            <div class="tag_classes mt-4 mb-5">
                @can('admin_lvl1')
                    <div class="alert alert-info">
                        As a Leaiia employee, you can tag relevant classes to share this announcement directly with students and
                        FI's
                        enrolled in those classes.
                    </div>
                @endcan
                <div class="my-4">
                    <label for="tag_classes" class="form-label text-secondary">Tag Classes</label>

                    <label class="form-label text-secondary d-none" id="select_all_container">
                        | Select All
                        <input type="checkbox" name="select_all" id="select_all">
                    </label>

                    <div class="d-flex align-items-center gap-2">
                        @foreach ($classes as $class)
                            <div class="input-group mb-2 d-flex align-items-center w-25">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0 tag-class-checkbox" type="checkbox"
                                        value="{{ $class->id }}" id="class_{{ $class->id }}" name="tag_classes[]"
                                        style="width: 20px; height: 24px;">
                                </div>
                                <input type="text" class="form-control" value="{{ $class->class_name }}" disabled>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-bullhorn"></i> Announce
            </button>

            @php
                $backUrl = Gate::allows('admin_lvl1') && $class_id == 0 ? route('class.index') : route('class.stream', ['class_id' => Crypt::encrypt($class_id)]);
            @endphp

            <a href="{{ $backUrl }}"
                class="btn btn-outline-danger">Back</a>
        </form>
    @else
        <form id="edit_announcement_form">
            <input type="hidden" name="edit_announcement_content" id="edit_announcement_content">
            <input type="hidden" name="class_id" value="{{ $class_id ?? null }}">
            <input type="hidden" name="announcement_id" value="{{ $announcement->id ?? null }}">
            <div id="toolbar" class="w-100">
                <button class="ql-bold" data-bs-toggle="tooltip" title="bold"></button>
                <button class="ql-italic" data-bs-toggle="tooltip" title="italic"></button>
                <button class="ql-underline" data-bs-toggle="tooltip" title="underline"></button>
                <button class="ql-list" value="bullet" data-bs-toggle="tooltip" title="list"></button>
                <button class="ql-clean" data-bs-toggle="tooltip" title="clear format"></button>
            </div>

            <div id="editor" style="height: 400px; width: 100%;" class="mb-2">
                {!! $announcement->content !!}
            </div>

            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-bullhorn"></i> Update Announcement
            </button>
            <a href="{{ route('class.stream', ['class_id' => $class_id]) }}" class="btn btn-outline-danger">Back</a>
        </form>
    @endif

@endsection

@section('scripts')
    <script>
        const selectAllContainer = document.getElementById('select_all_container');
        const selectAllCheckbox = document.getElementById('select_all');
        const classCheckboxes = document.querySelectorAll('.tag-class-checkbox');

        function updateSelectAllVisibility() {
            const anyChecked = Array.from(classCheckboxes).some(cb => cb.checked);
            selectAllContainer.classList.toggle('d-none', !anyChecked);
        }

        classCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateSelectAllVisibility();

                // If all checkboxes are selected manually, check "Select All"
                const allChecked = Array.from(classCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });

        selectAllCheckbox.addEventListener('change', () => {
            const isChecked = selectAllCheckbox.checked;
            classCheckboxes.forEach(cb => {
                cb.checked = isChecked;
            });
        });

        updateSelectAllVisibility();
    </script>
@endsection
