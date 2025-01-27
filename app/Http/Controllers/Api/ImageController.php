<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class ImageController extends Controller
{
   
    public function getCategories()
    {
        $categories = Category::all();

        return response()->json($categories);
    }
    public function showForm()
    {
        $categories = Category::all();
        return view('imageupload', compact('categories'));
    }
    

    public function uploadImage(Request $request)
    {
        $request->validate([
            'category_name' => 'required|exists:categories,id', 
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', 
        ]);

        $imageFile = $request->file('image');
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        $filePath = $imageFile->storeAs('images', $imageName, 'public');

        $image = new Image();
        $image->category_id = $request->category_name; 
        $image->file_path = $filePath;
        $image->mime_type = $imageFile->getClientMimeType();
        $image->file_size = $imageFile->getSize();
        $image->save();

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully!',
            'image_url' => asset('storage/' . $filePath),
        ]);
    }

    // public function showForm()
    // {
    //     $categories = Category::all();
    // //    dd($categories);
    

    // return view('imageupload', ['categories' => $categories]);
    // }
    
    // public function uploadimage(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'add_category' => 'required|string|max:255',
    //         'category_name' => 'required|string|max:255',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
    //     }

    //     $imageFile = $request->file('image');
    //     $imageName = time() . '_' . $imageFile->getClientOriginalName();
    //     $filePath = $imageFile->storeAs('images', $imageName, 'public');

    //     $image = Image::create([
    //         'add_category' => $request->add_category,
    //         'category_name' => $request->category_name,
    //         'file_path' => $filePath,
    //         'mime_type' => $imageFile->getClientMimeType(),
    //         'file_size' => $imageFile->getSize(),
    //     ]);

    //     $imageUrl = asset('storage/' . $filePath);

    //     return response()->json([
    //         'message' => 'Image uploaded successfully',
    //         'image' => $image,
    //         'image_url' => $imageUrl,
    //     ], 201);
    // }
   
    public function showimage($id)
    {
        $images = Image::find($id);

        if (!$images) {
            return response()->json([
                'message' => 'image not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($images);
    }


public function getAllImages()
{
    $images = Image::all();  
    $images->each(function($image) {
        $image->image_url = asset('storage/' . $image->file_path);
    });
    return response()->json($images);
}
}
