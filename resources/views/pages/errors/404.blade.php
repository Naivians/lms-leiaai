<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Not Found | LEIAAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom, #e0f7fa, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
        }
        .cloud {
            position: absolute;
            background: #fff;
            border-radius: 50%;
            box-shadow: 30px 20px 0 #fff, 60px 30px 0 #fff, 90px 10px 0 #fff;
            width: 100px;
            height: 60px;
            top: 10%;
            animation: float 30s linear infinite;
        }
        @keyframes float {
            from { left: -150px; }
            to { left: 100%; }
        }
        .airplane {
            width: 80px;
            transform: rotate(10deg);
        }
        .error-container {
            text-align: center;
            padding: 80px 20px;
        }
    </style>
</head>
<body>

    <!-- Clouds -->
    <div class="cloud" style="animation-delay: 0s;"></div>
    <div class="cloud" style="top: 20%; animation-delay: 5s;"></div>
    <div class="cloud" style="top: 30%; animation-delay: 10s;"></div>

    <div class="container error-container">
        <img src="https://cdn-icons-png.flaticon.com/512/3032/3032944.png" alt="Airplane Icon" class="airplane mb-4">
        <h1 class="display-1 text-primary fw-bold">404</h1>
        <h3 class="text-dark mb-3">You’ve flown off course</h3>
        <p class="text-muted mb-4">The page you’re looking for might have been grounded or doesn’t exist.</p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Return to Home</a>
    </div>

</body>
</html>
