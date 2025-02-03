<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        // Get the logged-in user's ID
        $user_id = Auth::id();
    
        // Get the cart from the session
        $cart = session('cart', []);
    
        // Log cart content to inspect its structure
        Log::info('Cart Data:', $cart);
    
        // Check if cart is empty
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
    
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
        ]);
    
        // Calculate the total order amount
        $total = 0;
        foreach ($cart as $item) {
            // Ensure each cart item has 'id', 'price', and 'quantity'
            if (!isset($item['id']) || !isset($item['price']) || !isset($item['quantity'])) {
                // Log missing data and return error
                Log::error('Missing cart item data', $item);
                return redirect()->route('cart.index')->with('error', 'One or more cart items are missing required information.');
            }
    
            // Calculate total price
            $total += $item['price'] * $item['quantity'];
        }
    
        // Store the order in the database
        $order = Order::create([
            'user_id' => $user_id,  
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'zip' => $request->input('zip'),
            'total' => $total,
            'status' => 'pending',
        ]);
    
        // Save each cart item to the 'order_items' table (use the relationship)
        foreach ($cart as $item) {
            if (isset($item['id'], $item['price'], $item['quantity'])) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }
    
        // Clear the cart from the session
        session()->forget('cart');
    
        // Send the order confirmation email
        Mail::to($request->input('email'))->send(new OrderConfirmationMail($order));
    
        // Redirect the user to the confirmation page with success message
        return redirect()->route('checkout.success')->with('success', 'Your order has been placed successfully!');
    }
    
    

}

