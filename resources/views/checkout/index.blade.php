<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Page Wrapper with Border and Shadow -->
    <div class="container mt-5 border shadow-lg p-4 rounded">

        <h2 class="text-center mb-4 text-success">Checkout</h2>

        <!-- Error Messages -->
        @if(session('error'))
        <div class="alert alert-danger text-center mb-4">{{ session('error') }}</div>
        @endif

        
        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        
            <div class="row mb-4">
                <div class="col-12">
                    <h5>Shipping Information</h5>
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                </div>
        
                <div class="col-12  mb-3">
                    <label for="address" class="form-label">Shipping Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
        
                <div class="col-12 col-md-6 mb-3">
                    <label for="zip" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="zip" name="zip" required>
                </div>
        
                <div class="mb-3">
                    <label for="discount_code">Discount Code</label>
                    <input type="text" id="discount_code" name="discount_code" class="form-control" placeholder="Enter your discount code">
                </div>

                {{-- <div class="mb-3">
                    <label for="payment_mode" class="form-label">Payment Mode</label>
                    <select class="form-control" id="payment_mode" name="payment_mode" required>
                        <option value="COD">Cash on Delivery</option>
                        <option value="Online">Online Payment</option>
                    </select>
                </div>
                
                <div class="mb-3" id="transaction_no_field" style="display: none;">
                    <label for="transaction_no" class="form-label">Transaction Number</label>
                    <input type="text" class="form-control" id="transaction_no" name="transaction_no">
                </div> --}}
                
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_mode" id="cod" value="COD" required>
                        <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_mode" id="online" value="Online">
                        <label class="form-check-label" for="online">Online Payment</label>
                    </div>
                </div>
                
                {{-- <div class="mb-3 d-none" id="transactionNoField">
                    <label for="transaction_no" class="form-label">Transaction Number</label>
                    <input type="text" class="form-control" id="transaction_no" name="transaction_no">
                </div> --}}
                
            </div>
        
            <hr>
        
            <div>
                <h5>Order Summary</h5>
                @php
                $originalPrice = $item['price'] ?? 0;
                $discount = $item['discount_code'] ?? 0; 
                $discountAmount = ($originalPrice * $discount) / 100;
                $finalPrice = $originalPrice - $discountAmount;
            @endphp
                <div class="mb-3">
                    @php $total = 0; @endphp
                    @foreach(session('cart', []) as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="Product Image" width="50" class="me-2">
                            </div>
                            <div>{{ $item['name'] }} (x{{ $item['quantity'] ?? 1 }})</div>
                            <div>
                                    ${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                                    ${{ number_format($finalPrice, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
        
                <div class="d-flex justify-content-between">
                    <div>Subtotal</div>
                    <div>${{ number_format($total, 2) }}</div>
                </div>
        
                @if(session('discount') > 0)
                    <div class="d-flex justify-content-between text-danger">
                        <div>Discount ({{ session('discount_code') }})</div>
                        <div>- ${{ number_format(session('discount', 0), 2) }}</div>
                    </div>
                @endif
        
                <hr>
        
                <div class="d-flex justify-content-between fw-bold">
                    <div>Total</div>
                    <div>${{ number_format(session('discounted_total', $total), 2) }}</div>
                </div>
            </div>
        
            <button type="submit" class="btn btn-success w-100 mt-4">Place Order</button>
        
        </form>
        
    </div>
<script>
//     document.addEventListener("DOMContentLoaded", function () {
//     const paymentModeRadios = document.querySelectorAll('input[name="payment_mode"]');
//     const transactionNoField = document.getElementById('transactionNoField');
//     const transactionNoInput = document.getElementById('transaction_no');

//     paymentModeRadios.forEach(radio => {
//         radio.addEventListener('change', function () {
//             if (this.value === "Online") {
//                 transactionNoField.classList.remove("d-none");
//                 transactionNoInput.setAttribute("required", "required");
//             } else {
//                 transactionNoField.classList.add("d-none");
//                 transactionNoInput.removeAttribute("required");
//                 transactionNoInput.value = ""; // Clear transaction number if not needed
//             }
//         });
//     });
// });

document.getElementById('payment_mode').addEventListener('change', function() {
    let transactionField = document.getElementById('transaction_no_field');
    if (this.value === 'Online') {
        transactionField.style.display = 'block';
    } else {
        transactionField.style.display = 'none';
    }
});
</script>
</body>

</html>
