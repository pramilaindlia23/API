<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;



class ReviewController extends Controller
{
//     public function store(Request $request)
// {
//     $request->validate([
//         'product_id' => 'required|exists:products,id',
//         'rating' => 'required|integer|min:1|max:5',
//         'review' => 'nullable|string',
//     ]);

//     try {
//         $rating = Review::create([
//             'product_id' => $request->product_id,
//             'user_id' => auth()->id() ?? null,  // Allow null user for guests
//             'rating' => $request->rating,
//             'review' => $request->review,
//         ]);

//         return response()->json(['message' => 'Rating submitted successfully!', 'rating' => $rating], 201);
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

public function store(Request $request)
{
    Log::info('Incoming Rating Data:', $request->all()); 

    try {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id() ?? 1,  
            'rating' => $request->rating,   
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
}
