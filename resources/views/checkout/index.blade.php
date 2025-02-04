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

        <form action="{{ route('checkout.store') }}" method="POST">
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
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-12 mb-3">
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
            </div>

            <hr>

            <div>
                <h5>Order Summary</h5>
                <div class="mb-3">
                    @php $total = 0; @endphp
                    @foreach(session('cart', []) as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <div>{{ $item['name'] }} (x{{ $item['quantity'] }})</div>
                        <div>${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                    </div>
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    @endforeach
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <div>Total</div>
                    <div>${{ number_format($total, 2) }}</div>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-4">Place Order</button>
        </form>
    </div>

</body>

</html>
