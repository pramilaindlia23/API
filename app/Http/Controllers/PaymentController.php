<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function showcheckout(){
        return view('payment.checkout');
    }

    public function processPayment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $charge = Charge::create([
                "amount" => $request->amount * 100, // Amount in cents
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment for Order #".rand(1000,9999),
            ]);

            return back()->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function cashOnDelivery(Request $request)
    {
    return response()->
    json(['message' =>
     'Order placed successfully with Cash on Delivery'
    ]);
   }


   public function upiPayment(Request $request)
{
    return response()->json(['message' =>
     'UPI payment initiated successfully. Scan the QR code to pay.'
    ]);
}
}

