<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class CartController extends Controller
{
    // Add product to the cart
    public function add($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $cart = session()->get('cart', []);

        //  increment its quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
           
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        
        session()->put('cart', $cart);

        return response()->json(['message' => 'Product added to cart', 'cart' => $cart]);
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
}
