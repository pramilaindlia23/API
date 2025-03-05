<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class CartController extends Controller
{

    public function __construct()
{
    $this->middleware('web'); 
}
public function add(Request $request, $id)
{
    $product = Product::find($id);

    if (!$product) {
        return redirect()->back()->with('error', 'Product not found.');
    }

    // Calculate Discounted Price
    $discountPercentage = $product->discount_code ?? 0; 
    $discountedPrice = $product->price - ($product->price * $discountPercentage / 100);

    $cart = session()->get('cart', []);

    if (!isset($cart[$id])) {
        $cart[$id] = [
            "name" => $product->name,
            "original_price" => $product->price, 
            "price" => $discountedPrice, 
            "quantity" => 0,
            "image" => $product->image
        ];
    }

    $cart[$id]['quantity']++;

    session()->put('cart', $cart);

    return redirect()->back()->with('success', 'Product added to cart.');
}
    
    // Show cart
    public function index()
    {
    $cart = session()->get('cart', []);

    return view('cart.index', compact('cart'));
    }
    
    // Remove product from cart
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart');
    }

    // Update product quantity in the cart
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }
    public function cartindex()
{
    $cart = session()->get('cart', []);
    return response()->json([
        'count' => count($cart),
        'items' => array_values($cart)
    ]);
}

}
