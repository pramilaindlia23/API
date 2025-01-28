<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoCat;
use Illuminate\Support\Facades\Log;



class VideoCatController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request to ensure the category name is provided
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Validation rule for category name
        ]);

        // Create a new video category record in the database
        $category = VideoCat::create([
            'category_name' => $validated['name'],
        ]);

        // Return a success response with the created category
        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category
        ], 201); // HTTP status code 201 means created
    }

    // Get all video categories
    public function index()
    {
        $categories = VideoCat::all(); // Get all categories from the database
        return response()->json($categories);
    }
    
}
