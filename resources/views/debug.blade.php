<!DOCTYPE html>
<html>
<head>
    <title>Debug Test</title>
</head>
<body>
    <h1>Debugging Example</h1>
    <ul>
        @foreach ($users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
    </ul>
</body>
</html>
