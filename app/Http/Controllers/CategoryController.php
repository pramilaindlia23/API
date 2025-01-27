<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class CategoryController extends Controller
{

    

    public function create()
    {
        $categories = Category::all();
        return view('imageupload', compact('categories'));
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'category_name' => $request->category_name,
        ]);

        return redirect()->route('category.create')->with('success', 'Category added successfully!');
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::find($request->category_id);

      
        $imagePath = $request->file('image')->store('category_images', 'public');

       
        $category->update(['image' => $imagePath]);

        return redirect()->route('category.create')->with('success', 'Image uploaded successfully!');
    }
            public function edit($id)
            {
                $category = Category::findOrFail($id);
                return view('category.editcategory', compact('category'));
            }

            public function update(Request $request, $id)
            {
                $category = Category::findOrFail($id);
        
                $request->validate([
                    'category_name' => 'required|string|max:255',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
        
                $category->category_name = $request->category_name;
        
                if ($request->hasFile('image')) {
                    
                    if ($category->image) {
                        Storage::delete('public/' . $category->image);
                    }
        
                    $category->image = $request->file('image')->store('category_images', 'public');
                }
        
                $category->save();
        
                return redirect()->route('category.create')->with('success', 'Category updated successfully!');
            }

            public function destroy($id)
            {
                $category = Category::findOrFail($id);

                
                if ($category->image) {
                    Storage::delete('public/' . $category->image);
                }

                $category->delete();

                return redirect()->route('category.create')->with('success', 'Category deleted successfully!');
            }
        
}
