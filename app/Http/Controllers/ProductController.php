<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\ProductCat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{

public function index()
{
    $products = Product::with('reviews')->get()->map(function ($product) {
        $discountAmount = $product->discounted_price ?? 0; 
        $product->final_price = $product->price - $discountAmount;

        $product->average_rating = $product->reviews()->avg('rating') ?? 0;

        return $product;
    });

    return view('products.index', compact('products'));
}

       public function create()
    {
        $categories = ProductCat::all(); 
        return view('products.create', compact('categories')); 
    }
    public function store(Request $request)
    {
        Log::info('Incoming Product Data:', $request->all());
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:products_cats,id',
            'price' => 'required|numeric|min:0',
            'discount_code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if (!$request->hasFile('image')) {
            Log::error('No image file found in request.');
            return response()->json(['error' => 'No image file found.'], 400);
        }
    
        $image = $request->file('image');
    
        if (!$image->isValid()) {
            Log::error('Invalid image file.');
            return response()->json(['error' => 'Invalid image file.'], 400);
        }
    
        // ✅ Save only the relative path
        $imagePath = $image->store('product_images', 'public');
    
        Log::info('Uploaded Image Path:', ['path' => $imagePath]);
    
        $product = Product::create([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'discount_code' => $validatedData['discount_code'] ?? null,
            'description' => $validatedData['description'] ?? null,
            'stock' => $validatedData['stock'],
            'image' => $imagePath,  // ✅ Save only the relative path
        ]);
    
        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product
        ]);
    }
    
    
//     public function store(Request $request)
// {
//     Log::info('Incoming Product Data:', $request->all());

//     $validatedData = $request->validate([
//         'name' => 'required|string|max:255',
//         'category_id' => 'required|exists:products_cats,id',
//         'price' => 'required|numeric|min:0',
//         'discount_code' => 'nullable|string|max:50',
//         'description' => 'nullable|string',
//         'stock' => 'required|integer|min:0',
//         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ensure single image
//     ]);

//     if (!$request->hasFile('image')) {
//         Log::error('No image file found in request.');
//         return response()->json(['error' => 'No image file found.'], 400);
//     }

//     $image = $request->file('image');

//     if (!$image->isValid()) {
//         Log::error('Invalid image file.');
//         return response()->json(['error' => 'Invalid image file.'], 400);
//     }

//     $imagePath = $image->store('public/product_images'); 
//     $imagePath = str_replace('public/', 'storage/', $imagePath); 

//     Log::info('Uploaded Image Path:', ['path' => $imagePath]);

//     $product = Product::create([
//         'name' => $validatedData['name'],
//         'category_id' => $validatedData['category_id'],
//         'price' => $validatedData['price'],
//         'discount_code' => $validatedData['discount_code'] ?? null,
//         'description' => $validatedData['description'] ?? null,
//         'stock' => $validatedData['stock'],
//         'image' => $imagePath, 
//     ]);

//     return response()->json([
//         'message' => 'Product created successfully!',
//         'product' => $product
//     ]);
// }

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
public function productsByCategory($id)
{
    $category = ProductCat::findOrFail($id); // Fetch category
    $products = Product::where('category_id', $id)->get(); // Fetch products by category

    return view('products.productCat', compact('category', 'products'));
}


}

