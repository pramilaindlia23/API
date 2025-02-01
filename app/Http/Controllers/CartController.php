<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class CartController extends Controller
{
    // Show the cart
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add($id)
{
    
    $product = Product::findOrFail($id);

    $cart = session()->get('cart', []);
    
    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->image,
        ];
    }

    session()->put('cart', $cart);

    return response()->json(['message' => 'Product added to cart successfully!', 'cart' => $cart]);
}
    // Remove item from the cart
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    // Update the quantity of an item in the cart
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }
}
