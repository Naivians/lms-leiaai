<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LEIAAI LMS | ' . $title)</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
    <script src="https://unpkg.com/libphonenumber-js@1.10.25/bundle/libphonenumber-js.min.js"></script>
    <style>
        .breadcrumb {
            background: transparent;
            font-size: 0.9rem;
        }
    </style>
</head>

<body style="background-color: #F4F5F7">
    {{-- modals --}}
    @include('partials.modal')
    @include('partials.messages')

    {{-- Navigations (Navbar) --}}
    <div class="nav_container">
        @include('partials.navbar')
    </div>

    <div class="main-content">
        <h2>

        </h2>

        <h2 class="mx-4 my-3 text-2xl font-semibold text-gray-700 dark:text-gray-300">
            @yield('class_title')
        </h2>
        @php
            $class_name = $class_name ?? 'nope';
        @endphp

        <header>
            <div class="card my-2 mx-3 {{ isset($title) && $title === 'Streams' ? 'd-none' : '' }}">
                <div class="card-header header_container  white-bg d-flex align-item-center justify-content-between">
                    <h1 class="fs-3 m-0 ">@yield('header_title')</h1>
                    @switch($title)
                        @case('User')
                            <div class="card-tools">
                                <a href="{{ route('user.register') }}" class="btn btn-primary btn-sm"><i
                                        class="fa-solid fa-plus me-2"></i> Add
                                    Users</a>
                            </div>
                        @break

                        @case('Classes')
                            @can('admin_lvl1')
                                <div class="card-tools">
                                    <div class="card-tools">
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addClassModal"><i class="fa-solid fa-plus me-2"></i> Create Class
                                        </button>
                                    </div>
                                </div>
                            @endcan
                        @break

                        @case('User Detailes')
                            <div class="card-tools">
                                <div class="card-tools">
                                    <a href="{{ route('user.index') }}" class="btn btn-danger btn-sm"><i
                                            class="fa-solid fa-arrow-left me-2"></i></i> Back
                                    </a>
                                </div>
                            </div>
                        @break

                        @case('Assessments')
                            <div class="card-tools">
                                <div class="card-tools">
                                    <a href="{{ route('assessment.create', ['class_id' => 0]) }}"
                                        class="btn btn-primary btn-sm">Create <i class="fa-solid fa-plus ms-1"></i></a>
                                </div>
                            </div>
                        @break

                        @case('Feedback')
                            <div class="card-tools">
                                <div class="card-tools">
                                    <a href="{{ route('class.feedback.create') }}" class="btn btn-primary btn-sm">Create <i
                                            class="fa-solid fa-plus ms-1"></i></a>
                                </div>
                            </div>
                        @break

                        @default
                    @endswitch
                </div>
            </div>
        </header>
        {{-- main content --}}
        <main class="my-2 mx-3 p-3 white-bg">
            @yield('content')
        </main>
    </div>
    {{-- sidebar --}}
    @include('partials.sidebar')
    {{-- scripts --}}

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <script src="https://unpkg.com/libphonenumber-js@1.10.22/bundle/libphonenumber-max.js"></script>

    @yield('js_imports')
    @yield('scripts')
    @yield('styles')

    <script>
        $("#editAssessmentForms").on("submit", function(e) {
            e.preventDefault();
            const form = new FormData(this);
            Swal.fire({
                title: "Ooopsss?",
                text: "Are you sure you want to update this assessment?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/assessments/update",
                        type: "POST",
                        data: form,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        beforeSend: function() {
                            pre_loader();
                        },
                        success: function(response) {
                            if (!response.success) {
                                error_message(response.message);
                                return;
                            }
                            success_message(response.message);
                            setTimeout(() => {
                                localStorage.setItem("total_question", 0);
                                localStorage.removeItem("assessment_data");
                                $("#questions_container").empty();
                                window.location.reload()
                            }, 1500);

                        },
                        error: function(error) {
                            alert(`error: ${error}`);
                        },
                    });
                }
            });
        });

        function deleteAssessments(assessment_id) {
            Swal.fire({
                title: "Ooopsss?",
                text: "Are you sure you want to delete this assessment?. exams and quizzes will be permanently deleted",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/assessments/destroyAssessment/${assessment_id}`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            pre_loader();
                        },
                        success: function(response) {

                            if (!response.success) {
                                error_message(response.message)
                                return
                            }
                            success_message(response.message)
                            refreshTable('assessments')
                            setTimeout(() => {
                                refreshTable('#assessments')
                            }, 1500);
                        },
                        error: function(error) {
                            alert(`error: ${error}`);
                        }
                    });
                }
            });
        }

        $("#createClassForm").on("submit", (e) => {
            e.preventDefault();
            let form = new FormData(e.target);

            if (form.get('course_name') == null || form.get('course_name') == '') {
                error_message("Please select a course for the class.");
                return
            }

            $.ajax({
                url: "/class/create",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                contentType: false,
                processData: false,
                data: form,
                success: (response) => {
                    if (!response.success) {
                        error_message("Failed to update user");
                        // console.log(response.message);
                        return;
                    }
                    success_message(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                    $("#createClassForm")[0].reset();

                    // console.log(response);
                    $("#errors").hide();

                },
                error: (xhr, status, error) => {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        $("#errors").show();
                        $("#errorList").empty();

                        if (response.errors) {
                            $("#errors").removeClass("d-none");
                            Object.values(response.errors)
                                .flat()
                                .forEach((msg) => {
                                    $("#errorList").append(
                                        `<li class="text-danger">${msg}</li>`
                                    );
                                });
                        } else if (response.message) {
                            $("#errors").removeClass("d-none");
                            $("#errorList").append(
                                `<li class="text-danger">${response.message}</li>`
                            );
                        } else {
                            $("#errors").removeClass("d-none");
                            $("#errorList").append(
                                `<li class="text-danger">An unknown error occurred.</li>`
                            );
                        }
                    } catch (e) {
                        console.error("An unexpected error occurred", e);
                        $("#errors").removeClass("d-none");
                        $("#errorList").html(
                            '<li class="text-danger">An unexpected error occurred</li>'
                        );
                    }
                },
            });
        });
        $(document).ready(function() {
            // users
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.index') }}',
                dom: 'Blfrtip',
                buttons: ['copy', 'csv', 'print', 'colvis'],
                language: {
                    emptyTable: "No Users found. Please add new users"
                },
                columns: [{
                        data: 'img',
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex align-item-center justify-content-center">
                            <img  src="${data}" width="50" height="50" style="border-radius:50%;">
                            </div>
                            `;
                        }
                    },
                    {
                        data: 'id_number'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'role_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'contact'
                    },
                    {
                        data: 'isVerified'
                    },
                    {
                        data: 'login_status',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#courseTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('course.index') }}',
                columns: [{
                        data: 'course_name',
                        name: 'course_name' // Add name for server-side processing
                    },
                    {
                        data: 'course_description',
                        name: 'course_description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return data || ''; // Handle empty action
                        }
                    }
                ],
                columnDefs: [{
                    targets: 2, // Action column (0-based index)
                    className: 'text-center' // Optional: center align
                }]
            });

            // LessonsTable
            $('#assessments').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('assessment.index') }}',
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'course'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: 'assessment_date'
                    },
                    {
                        data: 'assessment_time'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#feedback').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('class.feedback.index') }}',
                columns: [{
                        data: 'anonymous_name',
                    },
                    {
                        data: 'feedback'
                    },
                ]
            });

            // // progress
            $('#progress').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('assessment.show.progress') }}',
                dom: 'Blfrtip',
                buttons: ['copy', 'csv', 'print', 'colvis'],
                columns: [{
                        data: 'user_name'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: 'score'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
</body>

</html>
