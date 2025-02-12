<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class CheckoutController extends Controller
{
    
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
            'name' => 'required|string',
            'email' => 'required|email',
            'mobile' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'discount_code' => 'nullable|string',
        ]);
    
        $cart = session()->get('cart', []);
        
    
        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
        }
    
        // $total = 0;
        // foreach ($cart as $item) {
        //     $total += $item['price'] * $item['quantity'];
        // }
        $total = 0;

        foreach ($cart as $item) {
            $quantity = $item['quantity'] ?? 1; 
            $price = $item['price'] ?? 0; 

            $total += $price * $quantity;
        }
        // Check for discount
        $discount = 0;
        $discountCode = strtoupper($request->input('discount_code', ''));

        $discountMap = [
            '10' => 'DISCOUNT10',
            '20' => 'DISCOUNT20',
        ];
        if (array_key_exists($discountCode, $discountMap)) {
            $discountCode = $discountMap[$discountCode];
        }
        $validDiscounts = [
            'DISCOUNT10' => 0.10,
            'DISCOUNT20' => 0.20,
        ];
        if (array_key_exists($discountCode, $validDiscounts)) {
            $discount = $total * $validDiscounts[$discountCode];
        }
        
        $discountedTotal = max($total - $discount, 0);
    
        // Create order
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'total' => $discountedTotal,
            'discount_code' => $discountCode ?: null,
            'status' => 'pending',
        ]);
        // Store order items
        foreach ($cart as $productId => $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'product_name' => $item['name'],
                'image' => 'products_image/' . ($item['image'] ?? 'default.png'), // Correct path
 
            ]);
        }
        if ($request->hasFile('image')) {
            $orderItem = $request->file('image')->store('products_image', 'public');
        } else {
            $orderItem = 'products_image/default.png'; // Fallback image
        }
        // dd(session('cart'));

    
        Mail::to($request->email)->send(new OrderConfirmationMail($order));
    
        session()->forget('cart');
        // dd(session('cart'));

    
        session([
            'total' => $total, 
            'discount' => $discount,
            'discount_code' => $discountCode,
            'discounted_total' => $discountedTotal
        ]);
    
        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }
        public function confirmation($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Order not found.');
        }

        $order->load('items.product');

        return view('checkout.confirmation', compact('order'));
    }
    public function success()
    {
        $order = Order::latest()->first(); 
    
        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'No order found.');
        }
    
        return view('checkout.success', compact('order'));
    }
    private function getDiscount($discountCode, $total)
    {
        switch ($discountCode) {
            case 'DISCOUNT10':
                return 0.10 * $total;
            case 'DISCOUNT20':
                return 0.20 * $total;
        }
        return 0;
    }
}
