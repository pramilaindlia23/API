<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail; 
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;


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
    // Validate the request
    $request->validate([
        'payment_mode' => 'required|string|in:COD,Online',
        'transaction_no' => 'nullable|required_if:payment_mode,Online|string|max:255',
    ]);

    // Set payment details
    $paymentMode = $request->payment_mode;
    $transactionNo = ($paymentMode === "COD") ? "COD" : $request->transaction_no;

    // Create the order
    $order = Order::create([
        'user_id' => auth()->check() ? auth()->id() : null,
        'name' => $request->name,
        'email' => $request->email,
        'address' => $request->address,
        'city' => $request->city,
        'zip' => $request->zip,
        'mobile' => $request->mobile,
        'brand_name' => $request->brand_name,
        'total' => session('discounted_total', 0),
        'status' => 'pending',
        'payment_mode' => $transactionNo, 
    ]);

    session()->forget('cart');
    return redirect()->route('checkout.success', ['order' => $order->id])->with('success', 'Order placed successfully!');
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
    public function SpecificUser($id)
{
    $orders = Order::where('user_id', $id)->with('orderItems')->get();
    
    if ($orders->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No orders found for this user.'
        ], 404);
    }
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
    public function placeOrder(Request $request)
    {
        // Convert payment_mode to uppercase for consistency
        $request->merge(['payment_mode' => strtoupper($request->payment_mode)]);
    
        //  Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15', 
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_mode' => 'required|string|in:COD,ONLINE', 
            'transaction_no' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) use ($request) {
                if ($request->payment_mode === 'ONLINE' && empty($value)) {
                    $fail('Transaction number is required for online payments.');
                }
            }],
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        //  Set payment details
        $paymentMode = $request->payment_mode;
        $transactionNo = ($paymentMode === "ONLINE") ? $request->transaction_no : "COD";
    
        //  Calculate total and discount
        $discountPercentage = 0;
        if ($request->discount_code === 'SAVE10' || $request->discount_code === '10') {
            $discountPercentage = 10;
        } elseif ($request->discount_code === 'SAVE20' || $request->discount_code === '20') {
            $discountPercentage = 20;
        }
        
        $discountAmount = ($request->total * $discountPercentage) / 100;
        $finalTotal = $request->total - $discountAmount;
    
        //  Create order
        $order = Order::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'mobile' => $request->mobile, 
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'total' => $finalTotal,
            'discount_code' => $request->discount_code,
            'discount_amount' => $discountAmount,
            'payment_mode' => $paymentMode,
            'transaction_no' => $transactionNo,
            'status' => 'pending',
        ]);
    
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
    
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'image' => $product->image,
            ]);
        }
    
        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order->load('orderItems'),
        ], 201);
    }

}
