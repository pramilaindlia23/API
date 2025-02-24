<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow-lg">
        <h3 class="text-center">Stripe Payment</h3>
        <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
            @csrf
            <div class="mb-3">
                <label class="form-label">Enter Amount ($)</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Card Details</label>
                <div id="card-element" class="form-control"></div>
            </div>
            <button class="btn btn-success w-100 mt-3" id="submit">Pay Now</button>
        </form>
    </div>
</div>

{{-- <script>
    var stripe = Stripe("{{ env('STRIPE_KEY') }}");
    var elements = stripe.elements();
    var card = elements.create("card");
    card.mount("#card-element");

    var form = document.getElementById("payment-form");
    form.addEventListener("submit", function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                alert(result.error.message);
            } else {
                var hiddenInput = document.createElement("input");
                hiddenInput.setAttribute("type", "hidden");
                hiddenInput.setAttribute("name", "stripeToken");
                hiddenInput.setAttribute("value", result.token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });
</script> --}}

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', async function () {
        const stripe = Stripe('your_publishable_key'); 

        const { error, token } = await stripe.createToken(card);
        if (error) {
            alert(error.message);
            return;
        }

        fetch('/api/stripe-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                stripeToken: token.id,
                amount: document.getElementById('amount').value
            })
        })
        .then(res => res.json())
        .then(data => alert(data.message))
        .catch(error => console.error('Error:', error));
    });
</script>


</body>
</html>
