<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller; 

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoCat;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function upload(Request $request)
{
    try {
       
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:videocategory,id',
            'video' => 'required|mimes:mp4,avi,mkv|max:200000', 
        ]);

        
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoPath = $videoFile->storeAs('videos', time() . '.' . $videoFile->extension(), 'public');

            $video = Video::create([
                'title' => $validated['title'],
                'category_id' => $validated['category_id'],
                'video_path' => $videoPath,
            ]);

            return response()->json([
                'message' => 'Video uploaded successfully!',
                'video' => $video,
            ], 201);
        } else {
            return response()->json(['message' => 'No video file uploaded.'], 400);
        }

    } catch (\Exception $e) {
        return response()->json(['message' => 'Error uploading video', 'error' => $e->getMessage()], 500);
    }
}
public function index()
{
    $videos = Video::with('category')->get(); // Fetch all videos with their categories
    return response()->json($videos);
}

public function destroy($id)
{
    $video = Video::find($id);

    if (!$video) {
        return response()->json([
            'message' => 'Video not found'
        ], 404);
    }

    // Delete the video file
    Storage::delete('public/videos/' . $video->video_path);

    // Delete the video record from the database
    $video->delete();

    return response()->json([
        'message' => 'Video deleted successfully'
    ]);
    
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:video_cats,id',
    ]);

    $video = Video::find($id);

    if (!$video) {
        return response()->json([
            'message' => 'Video not found',
        ], 404);
    }

    $video->title = $validated['title'];
    $video->category_id = $validated['category_id'];
    $video->save();

    return response()->json([
        'message' => 'Video updated successfully!',
        'video' => $video,
    ]);
}
public function show($id)
{
    $video = Video::find($id);

    if (!$video) {
        return response()->json(['message' => 'Video not found'], 404);
    }

    return response()->json($video);
}

}