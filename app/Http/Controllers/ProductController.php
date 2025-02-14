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
//     public function index()
// {
//     $products = Product::all();
//     $products = $products->map(function ($product) {
//         $discountAmount = $product->discounted_price ?? 0; // If no discount, set 0
//         $product->final_price = $product->price - $discountAmount;
//         return $product;
//     });
//     $products = Product::with('reviews')->get()->map(function ($product) {
//         $product->average_rating = $product->reviews()->avg('rating') ?? 0;
//         return $product;
//     });
   
//     return response()->json($products);
// }
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

    // public function create()
    // {
    //     return view('products.create');
    // }
    public function create()
    {
        $categories = ProductCat::all(); 
        return view('products.create', compact('categories')); 
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'category_id' => 'required|exists:products_cats,id',
    //         'price' => 'required|numeric',
    //         'discount_code' => 'nullable|string',
    //         'description' => 'nullable|string',
    //         'stock' => 'required|integer|min:0',
    //         'images' => 'nullable|image|max:2048',
    //     ]);
    
    //     $discountPercentage = 0;
    //     if ($request->discount_code === 'SAVE10' || $request->discount_code === '10') {
    //         $discountPercentage = 10;
    //     } elseif ($request->discount_code === 'SAVE20' || $request->discount_code === '20') {
    //         $discountPercentage = 20;
    //     }
    
    //     $discountAmount = ($request->price * $discountPercentage) / 100;
    //     $discountedPrice = $request->price - $discountAmount;
    
    //     if ($discountPercentage == 0) {
    //         $discountAmount = 0;
    //         $discountedPrice = $request->price;
    //     }
    
    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('product_images', 'public');
    //     }
    
    //     $product = Product::create([
    //         'name' => $request->name,
    //         'category_id' => $request->category_id,
    //         'price' => $request->price,
    //         'discount_code' => $request->discount_code,  
    //         'discount_amount' => $discountAmount,  
    //         'discounted_price' => $discountedPrice,  
    //         'description' => $request->description,
    //         'stock' => $request->stock,
    //         'image' => $imagePath,
            
    //     ]);
    //     // dd($request->all()); 
    //     return redirect()->route('product.index')->with('success', 'Product added successfully!');

    //     // return response()->json(['message' =>
    //     //  'Product added successfully',
    //     //   'product' => $product],
    //     //    201);
    // }
    // public function store(Request $request)
    // {
        
    //         // Log request data for debugging
    //         Log::info('Product Store Request:', $request->all());
    
    //         // Validate form data
    //         $validatedData = $request->validate([
    //             'name' => 'required|string|max:255',
    //             'category_id' => 'required|exists:products_cats,id',
    //             'price' => 'required|numeric|min:0',
    //             'discount_code' => 'nullable|string|max:50',
    //             'description' => 'nullable|string',
    //             'stock' => 'required|integer|min:0',
    //             'images' => 'nullable|array',
    //             'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);
    
    //         // Calculate discount
    //         $discountPercentage = in_array($request->discount_code, ['SAVE10', '10']) ? 10 :
    //                               (in_array($request->discount_code, ['SAVE20', '20']) ? 20 : 0);
    //         $discountAmount = ($request->price * $discountPercentage) / 100;
    //         $discountedPrice = $request->price - $discountAmount;
    
    //         // Handle image uploads
    //         $imagePaths = [];
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $image) {
    //                 $path = $image->store('product_images', 'public'); // Stores in storage/app/public/product_images
    //                 $imagePaths[] = $path;
    //             }
    //         }
    
    //         // Create product
    //         $product = new Product();
    //         $product->name = $request->name;
    //         $product->category_id = $request->category_id;
    //         $product->price = $request->price;
    //         $product->discount_code = $request->discount_code;
    //         $product->description = $request->description;
    //         $product->stock = $request->stock;
    //         $product->images = json_encode([]); // Skip images for now
            
    //         if ($product->save()) {
    //             dd('Product saved successfully', $product);
    //         } else {
    //             dd('Failed to save product');
    //         }

    // }
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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ensure single image
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

    $imagePath = $image->store('public/product_images'); 
    $imagePath = str_replace('public/', 'storage/', $imagePath); 

    Log::info('Uploaded Image Path:', ['path' => $imagePath]);

    $product = Product::create([
        'name' => $validatedData['name'],
        'category_id' => $validatedData['category_id'],
        'price' => $validatedData['price'],
        'discount_code' => $validatedData['discount_code'] ?? null,
        'description' => $validatedData['description'] ?? null,
        'stock' => $validatedData['stock'],
        'image' => $imagePath, 
    ]);

    return response()->json([
        'message' => 'Product created successfully!',
        'product' => $product
    ]);
}

    
    
    // public function store(Request $request) {
    //     try {
    //         DB::beginTransaction(); // Start transaction
    
    //         Log::info('Received product store request', $request->all()); // Log request data
    
    //         $validatedData = $request->validate([
    //             'name' => 'required|string',
    //             'category_id' => 'required|exists:products_cats,id',
    //             'price' => 'required|numeric',
    //             'discount_code' => 'nullable|string',
    //             'description' => 'nullable|string',
    //             'stock' => 'required|integer|min:0',
    //             'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);
    
    //         Log::info('Validation passed', $validatedData); // Log validation success
    
    //         // Calculate discount
    //         $discountPercentage = in_array($request->discount_code, ['SAVE10', '10']) ? 10 :
    //                               (in_array($request->discount_code, ['SAVE20', '20']) ? 20 : 0);
    //         $discountAmount = ($request->price * $discountPercentage) / 100;
    //         $discountedPrice = $request->price - $discountAmount;
    
    //         // Store images
    //         $imagePaths = [];
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $image) {
    //                 $path = $image->store('product_images', 'public');
    //                 $imagePaths[] = $path;
    //                 Log::info('Image stored: ' . $path);
    //             }
    //         }
    
    //         // Create product
    //         $product = Product::create([
    //             'name' => $request->name,
    //             'category_id' => $request->category_id,
    //             'price' => $request->price,
    //             'discount_code' => $request->discount_code,
    //             'discount_amount' => $discountAmount,
    //             'discounted_price' => $discountedPrice,
    //             'description' => $request->description,
    //             'stock' => $request->stock,
    //             'images' => json_encode($imagePaths),
    //         ]);
    
    //         Log::info('Product created successfully', ['product_id' => $product->id]);
    
    //         DB::commit(); // Commit transaction
    
    //         return redirect()->route('product.index')->with('success', 'Product added successfully!');
    
    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Rollback if error occurs
    //         Log::error('Error saving product', ['error' => $e->getMessage()]);
    
    //         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }
    // public function store(Request $request) {
    //     try {
    //         DB::beginTransaction(); // Start transaction
    
    //         Log::info('Received product store request', $request->all());
    
    //         // Validate input
    //         $validatedData = $request->validate([
    //             'name' => 'required|string',
    //             'category_id' => 'required|exists:products_cats,id',
    //             'price' => 'required|numeric|min:0',
    //             'discount_code' => 'nullable|string',
    //             'description' => 'nullable|string',
    //             'stock' => 'required|integer|min:0',
    //             'images' => 'nullable|array', 
    //             'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);
    
    //         Log::info('Validation passed');
    
    //         // ✅ Calculate discount properly
    //         $discountPercentage = 0;
    //         if (!empty($request->discount_code)) {
    //             $discountPercentage = in_array($request->discount_code, ['SAVE10', '10']) ? 10 :
    //                                   (in_array($request->discount_code, ['SAVE20', '20']) ? 20 : 0);
    //         }
    //         $discountAmount = ($request->price * $discountPercentage) / 100;
    //         $discountedPrice = $request->price - $discountAmount;
    
    //         // ✅ Store images safely
    //         $imagePaths = [];
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $image) {
    //                 try {
    //                     $path = $image->store('product_images', 'public');
    //                     Log::info('Image stored at path: ' . $path);
    //                     $imagePaths[] = $path;
    //                 } catch (\Exception $e) {
    //                     Log::error('Error storing image: ' . $e->getMessage());
    //                     return redirect()->back()->with('error', 'Image upload failed: ' . $e->getMessage());
    //                 }
    //             }
    //         }
    
    //         Log::info('Images uploaded successfully', ['paths' => $imagePaths]);
    
    //         // ✅ Create product
    //         $product = Product::create([
    //             'name' => $validatedData['name'],
    //             'category_id' => $validatedData['category_id'],
    //             'price' => $validatedData['price'],
    //             'discount_code' => $validatedData['discount_code'] ?? null,
    //             'discount_amount' => $discountAmount,
    //             'discounted_price' => $discountedPrice,
    //             'description' => $validatedData['description'] ?? null,
    //             'stock' => $validatedData['stock'],
    //             'images' => json_encode($imagePaths),
    //         ]);
    
    //         if (!$product) {
    //             throw new \Exception('Product creation failed.');
    //         }
    
    //         Log::info('Product created successfully', ['product_id' => $product->id]);
    
    //         DB::commit(); // Commit transaction
    
    //         return redirect()->route('product.index')->with('success', 'Product added successfully!');
    
    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Rollback if error occurs
    //         Log::error('Error saving product', ['error' => $e->getMessage()]);
            
    //         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    //     }
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

