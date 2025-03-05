<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .message {
            padding: 20px;
            border-radius: 5px;
            display: inline-block;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .info { background-color: #cce5ff; color: #004085; }
    </style>
</head>
<body>
    <div class="message {{ $status }}">
        <h2>{{ $message }}</h2>
    </div>
</body>
</html>
