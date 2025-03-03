

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /* background: linear-gradient(135deg, #667eea, #764ba2); */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            border-radius: 10px;
        }
        .btn {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4 shadow-lg" style="max-width: 400px; margin: auto;">
            <h4 class="text-center mb-3">Forgot Password</h4>
    
            <!-- Step 1: Enter Email -->
            <div id="step1">
                <label for="email" class="fw-bold">Enter your Email</label>
                <input type="email" id="email" class="form-control mb-3" placeholder="Enter email" required>
                <button class="btn btn-primary w-100" onclick="sendOtp()">Send OTP</button>
            </div>
    
            <!-- Step 2: Enter OTP -->
            <div id="step2" class="d-none">
                <label for="otp" class="fw-bold">Enter OTP</label>
                <input type="text" id="otp" class="form-control mb-3" placeholder="Enter OTP" required>
                <button class="btn btn-success w-100" onclick="verifyOtp()">Verify OTP</button>
            </div>
    
            <!-- Step 3: Reset Password -->
            <div id="step3" class="d-none">
                <label for="password" class="fw-bold">New Password</label>
                <input type="password" id="password" class="form-control mb-2" placeholder="New password" required>
                <label for="password_confirmation" class="fw-bold">Confirm Password</label>
                <input type="password" id="password_confirmation" class="form-control mb-3" placeholder="Confirm password" required>
                <button class="btn btn-danger w-100" onclick="resetPassword()">Reset Password</button>
            </div>
    
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a>
            </div>
        </div>
    </div>
    
    <script>
        const API_URL = "{{ url('/api') }}"; 
    
        function sendOtp() {
            let email = document.getElementById("email").value;
            if (!email) {
                alert("Please enter your email!");
                return;
            }
    
            fetch(`${API_URL}/forgot-password`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById("step1").classList.add("d-none");
                document.getElementById("step2").classList.remove("d-none");
            })
            .catch(error => {
                alert("Something went wrong!");
            });
        }
    
        function verifyOtp() {
            let email = document.getElementById("email").value;
            let otp = document.getElementById("otp").value;
            if (!otp) {
                alert("Please enter OTP!");
                return;
            }
    
            fetch(`${API_URL}/verify-otp`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, otp })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById("step2").classList.add("d-none");
                document.getElementById("step3").classList.remove("d-none");
            })
            .catch(error => {
                alert("Invalid OTP!");
            });
        }
    
        function resetPassword() {
            let email = document.getElementById("email").value;
            let otp = document.getElementById("otp").value;
            let password = document.getElementById("password").value;
            let password_confirmation = document.getElementById("password_confirmation").value;
    
            if (!password || !password_confirmation) {
                alert("Please enter both password fields!");
                return;
            }
    
            fetch(`${API_URL}/reset-password`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, otp, password, password_confirmation })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.href = "{{ route('login') }}";
            })
            .catch(error => {
                alert("Failed to reset password!");
            });
        }
    </script>
</body>
</html>
