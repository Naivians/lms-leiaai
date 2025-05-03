<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My Laravel App')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    {{-- Navigations (Navbar) --}}
    <div class="main-content">
        @include('partials.navbar')

        <header>
            {{-- header title --}}
            <h1>@yield('header_title')</h1>
            <p>@yield('header_description')</p>
        </header>


        {{-- main content --}}
        <main>
            @yield('content')
        </main>

        <footer>
            <p>&copy; {{ date('Y') }} LEIAAI LMS</p>
        </footer>
    </div>


    {{-- sidebar --}}
    @include('partials.sidebar')

    {{-- scripts --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>

</body>

</html>
