<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return response()->json(ProductCat::all());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:products_cats|max:255'
        ]);
    
        $category = new ProductCat();
        $category->name = $validated['name'];
        $saved = $category->save();
    
        if ($saved) {
            return redirect()->back()->with('success', 'Category added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add category.');
        }

    }
    public function getCategories(){
        $categories = ProductCat::all();
        return response()->json([
            'success'=> true,
            'categories'=> $categories,
        ]);
    }

    
}
