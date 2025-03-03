<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h2>Hello, {{ $user->name }}</h2>
    <p>Click the button below to verify your email:</p>
    <a href="{{ $verificationUrl }}" style="display:inline-block; padding:10px 15px; background-color:green; color:white; text-decoration:none; border-radius:5px;">
        Verify Email
    </a>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
