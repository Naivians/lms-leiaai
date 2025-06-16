<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Enrollment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .container {
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .content {
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: white;
            background-color: #4CAF50;
            border-radius: 5px;
            text-decoration: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777777;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>🎉 Enrollment Confirmed</h2>
        </div>
        <div class="content">
            <p>Hi {{ $user->name ?? 'Student' }},</p>

            <p>Congratulations! You have been successfully enrolled in:</p>

            <ul>
                <li><strong>Class:</strong> {{ $class->class_name ?? 'TBA' }}</li>
                <li><strong>Course:</strong> {{ $class->course_name ?? 'TBA' }}</li>
                <li><strong>Instructor:</strong> {{ $user->name ?? 'Your Instructor' }}</li>
            </ul>

            <p>Your classes will begin on
                <strong>{{ $class->created_at ? $class->created_at->format('F j, Y') : 'TBD' }}</strong>. Please make
                sure to check the student portal for updated schedules, materials, and announcements.</p>


            <a href="{{ url('http://127.0.0.1:8000/') ?? '#' }}" class="button">Go to Class Portal</a>

            <p>If you have any questions, feel free to reach out to your instructor or contact our support team.</p>

            <p>Welcome aboard, and we look forward to a great term with you!</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Your School Name. All rights reserved.
        </div>
    </div>
</body>

</html>
