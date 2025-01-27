<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'add_category' => 'required|string|max:255',
        ]);

        $category = new Category();
        $category->category_name = $request->input('add_category');
        $category->save();

        return redirect()->back()->with('success', 'Category added successfully!');
    }
}
