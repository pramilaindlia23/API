<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
        ]);

        // Fetch the cart from the session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
        }

        // Calculate the total price
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(), 
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Save the order items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('order.confirmation', ['order' => $order->id]);
    }
}

