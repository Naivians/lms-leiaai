<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'LEIAAI LMS | ' . $title)</title>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


</head>

<body style="background-color: #F4F5F7">
    {{-- Navigations (Navbar) --}}
    <div class="main-content">
        @include('partials.navbar')

        <header>
            {{-- header title --}}
            <div class="header_container my-2 mx-3 p-3 white-bg d-flex align-item-center justify-content-between">
                <h1 class="fs-3 m-0 ">@yield('header_title')</h1>
                @switch($title)
                    {{-- add other pages here --}}
                    @case('User')
                        <a href="{{ route('user.Store') }}" class="btn btn-primary" style="width: 200px;">Add Users</a>
                    @break

                    @default
                @endswitch
            </div>
        </header>


        {{-- main content --}}
        <main class="my-2 mx-3 p-3 white-bg">
            @yield('content')
        </main>

        <footer class="my-2">
            <p>&copy; {{ date('Y') }} LEIAAI LMS. All rights reserved.
            </p>
        </footer>
    </div>


    {{-- sidebar --}}
    @include('partials.sidebar')

    {{-- scripts --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    @yield('scripts')
</body>

</html>
