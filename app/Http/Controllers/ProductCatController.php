<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCat;


class ProductCatController extends Controller
{
    public function index()
    {
        return response()->json(ProductCat::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:product_cats,name'
        ]);

        $category = ProductCat::create(['name' => $request->name]);

        return response()->json(['message' => 'Category added successfully!', 'category' => $category], 201);
    }
}
