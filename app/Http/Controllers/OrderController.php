<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Display the checkout page
    public function index()
    {
        // Get the cart from the session
        $cart = session()->get('cart', []);
        
        // If the cart is empty, redirect back with an error
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', compact('cart'));
    }

    // Process the order after the user submits the checkout form
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
        
        // If the cart is empty, redirect with an error
        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
        }

        // Calculate the total price of all items in the cart
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create a new order
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null, 
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'total' => $total,
            'status' => 'pending', // Default order status
        ]);

        // Save the items in the order_items table
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear the cart from the session
        session()->forget('cart');

        // Redirect to the order confirmation page
        return redirect()->route('order.confirmation', ['order' => $order->id]);
    }

    // Display the order confirmation page
    public function confirmation($orderId)
    {
        // Find the order by its ID
        $order = Order::find($orderId);

        // If the order doesn't exist, redirect with an error
        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Order not found.');
        }

        // Load order items (assuming Order has a relation to OrderItem)
        $order->load('items.product');

        return view('order.confirmation', compact('order'));
    }
}
