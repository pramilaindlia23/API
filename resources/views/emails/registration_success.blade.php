<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Registration Successful</h2>
    <p>Thank you, {{ $user->name }}! Your registration was successful.</p>
    <p>Thank you for registering. Please check your email for verification.</p>
    <p><strong>Email Status:</strong> {{ $email_status }}</p>
    {{-- <a href="/" class="btn btn-primary">Go Home</a> --}}
</body>
</html>
