<?php

namespace App\Http\Controllers;
use App\Models\VideoLink;


use Illuminate\Http\Request;

class VideoLinkController extends Controller
{
    // Get all video links
    public function index()
    {
        return response()->json(VideoLink::all());
    }
     // Store new video link
     public function store(Request $request)
     {
         $request->validate([
             'title' => 'required|string|max:255',
             'platform' => 'required|string|max:255',
             'url' => 'required|url',
         ]);
 
         $videoLink = VideoLink::create($request->all());
 
         return response()->json([
             'message' => 'Video link added successfully!',
             'videoLink' => $videoLink
         ]);
     }
     public function destroy($id)
    {
        $videoLink = VideoLink::findOrFail($id);
        $videoLink->delete();

        return response()->json(['message' => 'Video link deleted successfully!']);
    }
}
