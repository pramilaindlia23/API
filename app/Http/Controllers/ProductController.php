<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;


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
    $products = Product::with('reviews')->get()->map(function ($product) {
        $product->average_rating = $product->reviews()->avg('rating') ?? 0;
        return $product;
    });
   

    return response()->json($products);
}


// public function index()
// {
//     $products = Product::with('reviews')->get()->map(function ($product) {
//         if ($product->price > 0) {
//             $product->discount_percentage = round((($product->price - $product->discounted_price) / $product->price) * 100, 2);
//         } else {
//             $product->discount_percentage = 0;
//         }

//         $product->average_rating = $product->reviews()->avg('rating') ?? 0;
//         return $product;
//     });

//     return response()->json(['products' => $products]);
// }

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
public function applyDiscount(Request $request)
{
    $product = Product::find($request->product_id);
    
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $discountCode = $request->discount_code;
    $discountPercentage = 0;

   
    if ($discountCode === 'SAVE10') {
        $discountPercentage = 10;
    } elseif ($discountCode === 'SAVE20') {
        $discountPercentage = 20;
    }

   
    $discountedPrice = $product->price - ($product->price * $discountPercentage / 100);

    return response()->json([
        'original_price' => $product->price,
        'discount_code' => $discountCode, 
        'discount_percentage' => $discountPercentage, 
        'discounted_price' => round($discountedPrice, 2)
    ]);
}


}

