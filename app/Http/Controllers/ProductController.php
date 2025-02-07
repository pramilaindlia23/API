<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::all();
    $products = $products->map(function ($product) {
        $discountAmount = $product->discounted_price ?? 0; // If no discount, set 0
        $product->final_price = $product->price - $discountAmount;
        return $product;
    });

    return response()->json($products);
}


    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'price' => 'required|numeric',
        'discount_code' => 'nullable|string',
        'stock' => 'required|integer|min:0',
        'discounted_price' => 'nullable|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

    
    $discountedPrice = $request->discounted_price ?? 0.00;

    if ($request->discount_code === 'SAVE10') {
        $discountedPrice = $request->price * 0.10; 
    } elseif ($request->discount_code === 'SAVE20') {
        $discountedPrice = $request->price * 0.20; 
    }

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');
    }

    Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'discount_code' => $request->discount_code,
        'discounted_price' => $discountedPrice,
        'stock' => $request->stock,
        'description' => $request->description,
        'image' => $imagePath,
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}



}

