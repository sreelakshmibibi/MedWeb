<!DOCTYPE html>
<html>
<head>
    <title>Server Error</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>500 - Internal Server Error</h1>
        <p>Something went wrong on our end. Please try again later.</p>
        @if (config('app.debug'))
            <pre>{{ $exception->getMessage() }}</pre>
        @endif
        <a href="{{ url('/') }}">Return to Home</a>
    </div>
</body>
</html>