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

            <div class="d-flex justify-content-between">
                <div>Subtotal</div>
                <div>${{ number_format(session('total', 0), 2) }}</div>
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
                <div>${{ number_format(session('discounted_total', session('total', 0)), 2) }}</div>
            </div>
            <hr>
            @if(isset($order))
                <button class="btn btn-danger cancel-order float-left" data-order-id="{{ $order->id }}">
                    Cancel Order
                </button>
            @endif

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script>
            $(document).ready(function () {
                $(".cancel-order").click(function () {
                    let orderId = $(this).data("order-id");
            
                    if (!confirm("Are you sure you want to cancel this order?")) {
                        return;
                    }
            
                    $.ajax({
                        url: "/api/orders/" + orderId + "/cancel", 
                        type: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                        },
                        success: function (response) {
                            alert(response.message);
                            location.reload(); 
                        },
                        error: function (xhr) {
                            alert(xhr.responseJSON.message);
                        }
                    });
                });
            });
            </script>
            
    </body>

    </html>
