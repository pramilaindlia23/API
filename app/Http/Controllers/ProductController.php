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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_code' => 'nullable|numeric',
            'category_id' => 'required|exists:products_cats,id',
            'category_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'average_rating'=>'required|decimal',
            'total_reviews' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048'
        ]);
    
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_code = $request->discount_code;
        $product->category_id = $request->category_id;
        $product->category_name = $request->category_name;
        $product->brand_name = $request->brand_name;
        $product->average_rating = $request->average_rating;
        $product->total_reviews = $request->total_reviews;
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $product->image = $imagePath;
        }
    
        $product->save();
    
        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
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
public function productsByCategory($id)
{
    $category = ProductCat::findOrFail($id); // Fetch category
    $products = Product::where('category_id', $id)->get(); // Fetch products by category

    return view('products.productCat', compact('category', 'products'));
}
public function getProducts()
{
    $products = Product::all();

    // Fix image path before sending response
    foreach ($products as $product) {
        $product->image = asset('storage/' . $product->image);
    }
    return response()->json($products);
}

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        return response()->json($products);
    }

    public function categoryProducts($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for this category.'], 404);
        }

        return response()->json($products);
    }


    public function showCategoryProducts($id)
{
    $products = Product::where('category_id', $id)->get();
    return view('products.productCat', compact('products', 'id'));
}

public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all()); 

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }
    public function show($id)
{
    $product = Product::findOrFail($id);
    $reviews = Review::where('product_id', $id)->latest()->get();

    return view('products.show', compact('product', 'reviews'));
}

public function details($id)
{
    // Fetch product details with reviews
    $product = Product::with('reviews')->find($id);

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    // Calculate total reviews and average rating
    $totalReviews = $product->reviews->count();
    $averageRating = $totalReviews > 0 ? round($product->reviews->avg('rating'), 1) : 0;

    return response()->json([
        'message' => 'Product details fetched successfully',
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'discount_code' => $product->discount_code,
            'category_id' => $product->category_id,
            'category_name' => $product->category_name,
            'brand_name' => $product->brand_name,
            'image' => $product->image ? asset('storage/' . $product->image) : null,
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating,
        ],
        'reviews' => $product->reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user_name' => $review->user->name ?? 'Anonymous',
                'rating' => $review->rating,
                'review' => $review->review,
                'created_at' => $review->created_at->format('Y-m-d H:i:s'),
            ];
        }),
    ]);
}

// public function showreview($id)
// {
//     $product = Product::with('reviews')->find($id);

//     if (!$product) {
//         return response()->json(['error' => 'Product not found'], 404);
//     }

//     return response()->json([
//         'message' => 'Product details fetched successfully',
//         'product' => $product,
//         'total_reviews' => (int) $product->total_reviews, // Total reviews count
//         'average_rating' =>(float) $product->average_rating, // Average rating
//     ]);
// }



public function showreview($id)
{
    $product = Product::with(['reviews.user'])->find($id);

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    return response()->json([
        'message' => 'Product details fetched successfully',
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'total_reviews' => (int) $product->reviews->count(), // Total reviews count
            'average_rating' => (float) $product->reviews->avg('rating'), // Average rating
        ],
        
    ]);
}

}

