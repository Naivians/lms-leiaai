<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.jpg') }}">
    <title>LEIAAI LMS | PDF</title>

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <iframe id="pdf-viewer" src="{{ url('pdfjs/web/viewer.html') }}?file={{ urlencode($pdf_url) }}" width="100%"
        style="height: 100vh; border: none;">
    </iframe>
</body>

</html>
