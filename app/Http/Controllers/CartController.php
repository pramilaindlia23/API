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
    // Add product to the cart
    // public function add(Request $request, $id)
    // {
    //     $product = Product::find($id);
    
    //     if (!$product) {
    //         return redirect()->back()->with('error', 'Product not found.');
    //     }
    
    //     // Fetch cart session
    //     $cart = session()->get('cart', []);
    
    //     // Ensure "quantity" key exists
    //     if (!isset($cart[$id])) {
    //         $cart[$id] = [
    //             "name" => $product->name,
    //             "price" => $product->price,
    //             "quantity" => 1 
    //         ];
    //     } else {
    //         $cart[$id]['quantity']++;
    //     }
    
    //     session()->put('cart', $cart);
    
    //     return redirect()->back()->with('success', 'Product added to cart.');
    // }
//     public function add(Request $request, $id)
// {
//     $product = Product::find($id);

//     if (!$product) {
//         return redirect()->back()->with('error', 'Product not found.');
//     }

//     $cart = session()->get('cart', []);

//     if (!isset($cart[$id])) {
//         $cart[$id] = [
//             "name" => $product->name,
//             "price" => $product->price,
//             "quantity" => 1 
//         ];
//     } else {
//         $cart[$id]['quantity']++;
//     }

//     session()->put('cart', $cart);

//     return redirect()->back()->with('success', 'Product added to cart.');
// }
// public function add(Request $request, $id)
// {
//     $product = Product::find($id);

//     if (!$product) {
//         return redirect()->back()->with('error', 'Product not found.');
//     }

//     // Fetch cart session
//     $cart = session()->get('cart', []);

//     // Ensure "quantity" key exists
//     if (!isset($cart[$id])) {
//         $cart[$id] = [
//             "name" => $product->name,
//             "price" => $product->price,
//             "quantity" => 1 // Ensure quantity starts at 1
//         ];
//     } else {
//         // Increase quantity if already in cart
//         $cart[$id]['quantity']++;
//     }

//     // Save updated cart back to session
//     session()->put('cart', $cart);

//     return redirect()->back()->with('success', 'Product added to cart.');
// }

// public function add(Request $request, $id)
// {
//     $product = Product::find($id);

//     if (!$product) {
//         return redirect()->back()->with('error', 'Product not found.');
//     }

//     // Fetch cart session
//     $cart = session()->get('cart', []);

//     // Ensure "quantity" key exists
//     if (!isset($cart[$id])) {
//         $cart[$id] = [
//             "name" => $product->name,
//             "price" => $product->price,
//             "quantity" => 0,
//             "image" => asset('storage/' . $product->image) // Ensure full path is stored
//         ];
//     }

//     // Increase quantity
//     $cart[$id]['quantity']++;

//     // Save updated cart back to session
//     session()->put('cart', $cart);  
//     dd(session('cart'));


//     return redirect()->back()->with('success', 'Product added to cart.');
// }
public function add(Request $request, $id)
{
    $product = Product::find($id);

    if (!$product) {
        return redirect()->back()->with('error', 'Product not found.');
    }

    $cart = session()->get('cart', []);

    if (!isset($cart[$id])) {
        $cart[$id] = [
            "name" => $product->name,
            "price" => $product->price,
            "quantity" => 0,
            "image" => $product->image // Ensure image is added
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
}
