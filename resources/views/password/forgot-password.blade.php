<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h4 class="text-center mb-3">Forgot Password</h4>
    
            <!-- Step 1: Enter Email -->
            <div id="step1">
                <label for="email">Enter your Email</label>
                <input type="email" id="email" class="form-control mb-2" placeholder="Enter email" required>
                <button class="btn btn-primary w-100" onclick="sendOtp()">Send OTP</button>
            </div>
    
            <!-- Step 2: Enter OTP -->
            <div id="step2" class="d-none">
                <label for="otp">Enter OTP</label>
                <input type="text" id="otp" class="form-control mb-2" placeholder="Enter OTP" required>
                <button class="btn btn-success w-100" onclick="verifyOtp()">Verify OTP</button>
            </div>
    
            <!-- Step 3: Reset Password -->
            <div id="step3" class="d-none">
                <label for="password">New Password</label>
                <input type="password" id="password" class="form-control mb-2" placeholder="New password" required>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" class="form-control mb-2" placeholder="Confirm password" required>
                <button class="btn btn-danger w-100" onclick="resetPassword()">Reset Password</button>
            </div>
    
            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
    
    <script>
        const API_URL = "{{ url('/api') }}"; 
        // Send OTP
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
    
        // Verify OTP
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
    
        //  Reset Password
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