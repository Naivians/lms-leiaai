<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LEIAAI LMS | ' . $title)</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.jpg') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/assessment.css') }}">
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

    @yield('style')
</head>

<body>
    <img src="{{ asset('assets/img/bg-cloud1.png') }}" alt="" id="bg-cloud1">
    <img src="{{ asset('assets/img/bg-cloud1.png') }}" alt="" id="bg-cloud2">
    <img src="{{ asset('assets/img/bg-cloud1.png') }}" alt="" id="bg-cloud3">
    <img src="{{ asset('assets/img/plane 1.png') }}" alt="" id="plane_1">
    <div class="airplane"></div>

    <main>
        @yield('content')
    </main>

    {{-- scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>

    @yield('script')

</body>

</html>
