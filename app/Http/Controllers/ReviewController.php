<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;



class ReviewController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            // 'user_name' => 'required|exists:users,name',

            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
            'title' => 'required|string',
        ]);
    
        // Save the review
        $review = new Review();
        $review->product_id = $request->product_id;
        $review->user_id = $request->user_id;
        // $review->user_name =$request->user_name;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->title = $request->title;
        $review->save();
    
        // Update product rating and review count
        $product = Product::find($request->product_id);
        $product->total_reviews = Review::where('product_id', $request->product_id)->count();
        $product->average_rating = Review::where('product_id', $request->product_id)->avg('rating') ?? 0;
        $product->save();
    
        return response()->json(['message' => 'Review added successfully', 'review' => $review]);
    }
    
public function index() {
        $products = Product::with('reviews')->get();
        return response()->json($products);
    }

    public function getAllRatings()
    {
        try {
            $ratings = Review::with('product')->get(); // Load associated product data
            
            return response()->json([
                'success' => true,
                'ratings' => $ratings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

//     public function getReviews($productId)
// {
//     $reviews = Review::where('product_id', $productId)
//         ->with('user') // Ensure user data is included
//         ->get();

//     return response()->json(['reviews' => $reviews]);
// }
}
