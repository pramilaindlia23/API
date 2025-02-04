<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail; 

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

    public function store(Request $request)
{
   
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:255',
        'zip' => 'required|string|max:20',
    ]);

    
    $cart = session()->get('cart', []);

   
    \Log::info('Cart Data:', $cart);

   
    if (empty($cart)) {
        return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
    }

   
    $total = 0;

    
    foreach ($cart as $item) {
        if (!isset($item['id'], $item['price'], $item['quantity'])) {
           
            \Log::error('Missing cart item data', $item);
            return redirect()->route('cart.index')->with('error', 'One or more cart items are missing required information.');
        }

      
        $total += $item['price'] * $item['quantity'];
    }

    // Create a new order in the database
    $order = Order::create([
        'user_id' => auth()->check() ? auth()->id() : null,
        'name' => $request->name,
        'email' => $request->email,
        'address' => $request->address,
        'city' => $request->city,
        'zip' => $request->zip,
        'total' => $total,
        'status' => 'pending',
    ]);

    // Save each cart item as an order item
    foreach ($cart as $item) {
        if (isset($item['id'], $item['price'], $item['quantity'])) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'], // Ensure this is the correct product ID
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }

    // Clear the cart from the session
    session()->forget('cart');

    // Redirect to the confirmation page
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

        // Load order items and their associated products
        $order->load('items.product');

        return view('order.confirmation', compact('order'));
    }
}
