    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order Confirmation</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light">
        <div class="container mt-5 border shadow-lg p-4 rounded">
            <h2 class="text-center mb-4 text-success">Order Confirmation</h2>

            <div class="alert alert-success text-center mb-4">
                Your order has been successfully placed! Thank you for shopping with us.
            </div>

            <div class="mb-3">
                <h5>Order Summary</h5>
                <ul class="list-group">
                    @foreach(session('cart', []) as $item)
                    <li class="list-group-item">
                        {{ $item['name'] }} (x{{ $item['quantity'] }}) - ${{ number_format($item['price'] * $item['quantity'], 2) }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="d-flex justify-content-between fw-bold">
                <div>Total</div>
                <p><strong>Original Total:</strong> ${{ number_format($total + $discount, 2) }}</p> 
            
                @if ($discount > 0)
                    <p><strong>Discount Applied:</strong> ${{ number_format($discount, 2) }}</p>
                @endif
            </div>
            
            <div class="text-center mt-4">
                <p><strong>Final Total:</strong> ${{ number_format($total, 2) }}</p>
                <p><strong>Discount Code Used:</strong> {{ $order->discount_code ?? 'None' }}</p>
            </div>
    </body>

    </html>
