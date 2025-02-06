<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // dd($products);
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
        'description' => 'nullable|string',
        'discount_code' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
        'stock' => 'required|integer|min:0'
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');
    }

    Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'discount_code' => $request->discount_code,
        'image' => $imagePath,
        'stock' => $request->stock
    ]);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}




}

