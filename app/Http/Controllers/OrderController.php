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
        
        $cart = session()->get('cart', []);

       
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

    foreach ($cart as $item) {
        if (isset($item['id'], $item['price'], $item['quantity'])) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'], 
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }

    session()->forget('cart');

    return redirect()->route('order.confirmation', ['order' => $order->id]);
}
    public function confirmation($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Order not found.');
        }

        $order->load('items.product');

        return view('order.confirmation', compact('order'));
    }

    public function show()
    {
        $orders = Order::with('orderItems')->get();
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }
    public function cancelOrder($id)
    {
        $order = Order::find($id);
    
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        if ($order->status === 'canceled') {
            return response()->json(['message' => 'Order is already canceled'], 400);
        }
    
        $order->status = 'canceled';
        $order->save();
    
        return response()->json(['message' => 'Order canceled successfully', 'order' => $order], 200);
    }
 
}
