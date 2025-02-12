<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\ProductCat;



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
    // public function create()
    // {
    //     return view('products.create');
    // }
    public function create()
{
    $categories = ProductCat::all();
    return view('products.create', compact('categories'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:products_cats,id',
            'price' => 'required|numeric',
            'discount_code' => 'nullable|string',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $discountPercentage = 0;
        if ($request->discount_code === 'SAVE10' || $request->discount_code === '10') {
            $discountPercentage = 10;
        } elseif ($request->discount_code === 'SAVE20' || $request->discount_code === '20') {
            $discountPercentage = 20;
        }
    
        $discountAmount = ($request->price * $discountPercentage) / 100;
        $discountedPrice = $request->price - $discountAmount;
    
        if ($discountPercentage == 0) {
            $discountAmount = 0;
            $discountedPrice = $request->price;
        }
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }
    
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'discount_code' => $request->discount_code,  
            'discount_amount' => $discountAmount,  
            'discounted_price' => $discountedPrice,  
            'description' => $request->description,
            'stock' => $request->stock,
            'image' => $imagePath,
            
        ]);
        // dd($request->all()); 
        return redirect()->route('product.index')->with('success', 'Product added successfully!');

        // return response()->json(['message' =>
        //  'Product added successfully',
        //   'product' => $product],
        //    201);
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

