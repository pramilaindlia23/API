<?php

// app/Http/Controllers/Api/VideoController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:video_cats,id', // Ensure category exists
            'video' => 'required|file|mimes:mp4,avi,mkv|max:10240', // Max file size of 10MB
        ]);

        // Store the video file
        $videoFile = $request->file('video');
        $videoName = time() . '_' . $videoFile->getClientOriginalName();
        $filePath = $videoFile->storeAs('videos', $videoName, 'public');

        // Create a new video record in the database
        $video = Video::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'file_path' => $filePath,
            'mime_type' => $videoFile->getClientMimeType(),
            'file_size' => $videoFile->getSize(),
        ]);

        return response()->json(['message' => 'Video uploaded successfully!', 'video' => $video], 201);
    }

    public function index()
    {
        $videos = Video::with('category')->get(); // Include category data with the video
        return response()->json($videos);
    }
    

}