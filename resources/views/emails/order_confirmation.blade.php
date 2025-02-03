<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>

<body>
    <h1>Thank you for your order, {{ $order->name }}!</h1>
    <p>Your order has been successfully placed. Here are your order details:</p>

    <h3>Order Summary:</h3>
    <ul>
        @foreach($order->items as $item)
        <li>{{ $item->product->name }} (x{{ $item->quantity }}) - ${{ number_format($item->price * $item->quantity, 2) }}</li>
        @endforeach
    </ul>

    <hr>

    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>

    <p>We will notify you once your order is shipped. Thank you for shopping with us!</p>
</body>

</html>
