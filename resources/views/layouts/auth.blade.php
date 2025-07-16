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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @yield('style')


    <style>
        /* Light aviation-themed background */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e6f0ff 0%, #c2e2ff 50%, #a8d4ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Form container */
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 82, 217, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h1 {
            color: #1a73e8;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #5f6368;
            font-size: 15px;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 24px;
        }

        .form-control {
            border-radius: 8px;
            padding: 14px 16px;
            border: 1px solid #dadce0;
            width: 100%;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
        }

        /* Buttons */
        .btn-login {
            background: linear-gradient(90deg, #0052D9 0%, #1A73E8 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            width: 100%;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 82, 217, 0.25);
        }

        .login-links {
            margin-top: 24px;
            font-size: 14px;
        }

        .login-links a {
            color: #1a73e8;
            text-decoration: none;
            transition: all 0.2s;
        }

        .login-links a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 24px;
            }
        }
    </style>

</head>

<body>
    <main>
        @yield('content')
    </main>
    {{-- scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
