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
        'discounted_price' => 'required|numeric',
        'description' => 'nullable|string',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
    ]);

    $discountAmount = $request->price - $request->discounted_price;

    // dd([
    //     'Price' => $request->price,
    //     'Discounted Price' => $request->discounted_price,
    //     'Calculated Discount Amount' => $discountAmount,
    // ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');
    }

    Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'discounted_price' => $request->discounted_price,
        'discount_amount' => $discountAmount,  
        'description' => $request->description,
        'stock' => $request->stock,
        'image' => $imagePath,
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}



}

