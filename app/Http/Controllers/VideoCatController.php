<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoCat;
use Illuminate\Support\Facades\Log;



class VideoCatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $category = VideoCat::create([
            'category_name' => $validated['name'],
        ]);
    
        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category,
        ], 201);
    }
    
    public function index()
    {
        $categories = VideoCat::all();
        return response()->json($categories);
    }

    
    
    
}
