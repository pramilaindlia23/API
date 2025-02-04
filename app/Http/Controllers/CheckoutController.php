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
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'discount_code' => 'nullable|string',
        ]);
    
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Your cart is empty.');
        }
    
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    
        $discount = 0;
        if ($request->has('discount_code')) {
            $discountCode = $request->input('discount_code');
            if ($discountCode == 'DISCOUNT10') {
                $discount = 0.10 * $total;
            } elseif ($discountCode == 'DISCOUNT20') {
                $discount = 0.20 * $total;
            }
        }
    
        $total -= $discount;
    
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null, 
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'total' => $total,
            'discount_code' => $discountCode ?? null, 
            'status' => 'pending', 
        ]);
    
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    
        Mail::to($request->email)->send(new OrderConfirmationMail($order));
    
        // Clear the cart
        session()->forget('cart');
    
       
        return redirect()->route('checkout.success', ['total' => $total, 'discount' => $discount, 'order' => $order]);
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

    public function success(Request $request)
    {
        $order = Order::latest()->first();
    
        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'No order found.');
        }
    
        $total = $order->total;
        // dd($total);
    
        return view('checkout.success', compact('order', 'total'));

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
