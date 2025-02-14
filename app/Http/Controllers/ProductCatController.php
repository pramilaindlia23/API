<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\ProductCat;

class ProductCatController extends Controller
{
    public function create()
    {
        $categories = ProductCat::all();
        return view('product.create', compact('products_cats'));
    }
    public function index()
    {
        $categories = ProductCat::all();
        return response()->json(['success' => true, 'categories' => $categories]);
    }
    
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|unique:products_cats|max:255'
            ]);

            // Create category
            $category = new ProductCat();
            $category->name = $validated['name'];
            $saved = $category->save();

            if ($saved) {
                return redirect()->back()->with('success', 'Category added successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to add category.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getCategories(){
        $categories = ProductCat::all();
        return response()->json([
            'success'=> true,
            'categories'=> $categories,
        ]);
    }

    // public function index()
    // {
    //     return response()->json(ProductCat::all());
    // }
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|unique:products_cats|max:255'
    //     ]);
    
    //     $category = new ProductCat();
    //     $category->name = $validated['name'];
    //     $saved = $category->save();
    
    //     if ($saved) {
    //         return redirect()->back()->with('success', 'Category added successfully!');
    //     } else {
    //         return redirect()->back()->with('error', 'Failed to add category.');
    //     }

    // }
    
}
