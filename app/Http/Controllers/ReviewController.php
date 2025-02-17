<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;



class ReviewController extends Controller
{

// public function store(Request $request)
// {
//     Log::info('Incoming Rating Data:', $request->all()); 

//     try {
//         $request->validate([
//             'product_id' => 'required|exists:products,id',
//             'rating' => 'required|integer|min:1|max:5',
//         ]);

//         $review = Review::create([
//             'product_id' => $request->product_id,
//             'user_id' => auth()->id() ?? 1,  
//             'rating' => $request->rating,   
//         ]);

//         Log::info('Rating Saved:', $review->toArray()); 

//         return response()->json(['message' => 'Rating submitted successfully!', 'review' => $review], 201);
//     } catch (\Exception $e) {
//         Log::error('Error saving rating:', ['error' => $e->getMessage()]); 
//         return response()->json(['error' => 'Something went wrong.', 'details' => $e->getMessage()], 500);
//     }
// }

 
public function store(Request $request)
{
    Log::info('Incoming Rating Data:', $request->all());

    try {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255', // Validate title
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id() ?? 1,
            'rating' => $request->rating,
            'review' => $request->review,
            'title' => $request->title, // Save title
        ]);

        Log::info('Rating Saved:', $review->toArray());

        return response()->json(['message' => 'Rating submitted successfully!', 'review' => $review], 201);
        } catch (\Exception $e) {
        Log::error('Error saving rating:', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Something went wrong.', 'details' => $e->getMessage()], 500);
    }
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
}
