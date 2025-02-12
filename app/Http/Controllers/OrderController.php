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
        'mobile' => 'required',
        'brand_name' => 'required',
        'product_image' => 'nullable|image|max:2048',
    ]);
    $imagePath = null;
    if ($request->hasFile('product_image')) {
        $imagePath = $request->file('product_image')->store('products', 'public');
    }

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
        'mobile' => $request->mobile,
        'brand_name' => $request->brand_name,
        'product_image' => $imagePath,
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
    return redirect()->route('checkout.success',['order' => $order->id])->with('success', 'Order placed successfully!');

    // return redirect()->route('order.confirmation', ['order' => $order->id]);
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

//     public function placeOrder(Request $request)
// {
//     // Validate request
//     $validator = Validator::make($request->all(), [
//         'user_id' => 'required|exists:users,id',
//         'name' => 'required|string|max:255',
//         'email' => 'required|email',
//         'address' => 'required|string',
//         'city' => 'required|string',
//         'zip' => 'required|string',
//         'items' => 'required|array',
//         'items.*.product_id' => 'required|exists:products,id',
//         'items.*.quantity' => 'required|integer|min:1',
//         'total' => 'required|numeric|min:0',
//         'discount_code' => 'nullable|string',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     // Create order
//     $order = Order::create([
//         'user_id' => $request->user_id,
//         'name' => $request->name,
//         'email' => $request->email,
//         'address' => $request->address,
//         'city' => $request->city,
//         'zip' => $request->zip,
//         'total' => $request->total,
//         'discount_code' => $request->discount_code,
//         'status' => 'pending',
//     ]);

//     // Insert multiple products in order_items table
//     foreach ($request->items as $item) {
//         $product = Product::find($item['product_id']);
//         if ($product) {
//             OrderItem::create([
//                 'order_id' => $order->id,
//                 'product_id' => $product->id,
//                 'quantity' => $item['quantity'],
//                 // Assuming price is stored in products table
//             ]);
//         }
//     }

//     return response()->json([
//         'message' => 'Order placed successfully',
//         'order' => $order,
//         'order_items' => $order->orderItems // Retrieve related items
//     ], 201);
// }

    


    public function placeOrder(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'discount_code' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Create order
        $order = Order::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'items' => json_encode($request->items), 
            'total' => $request->total,
            'discount_code' => $request->discount_code,
            'status' => 'pending',
        ]);
    
        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
    }
    

    
}
