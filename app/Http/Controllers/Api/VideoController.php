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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,avi,mkv|max:10240', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $videoFile = $request->file('video');
        $videoName = time() . '_' . $videoFile->getClientOriginalName();
        $filePath = $videoFile->storeAs('videos', $videoName, 'public');

        $video = Video::create([
            'title' => $request->title,
            'file_path' => $filePath,
            'mime_type' => $videoFile->getClientMimeType(),
            'file_size' => $videoFile->getSize(),
        ]);

        return response()->json([
            'message' => 'Video uploaded successfully',
            'video' => $video,
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                'message' => 'Video not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($video);
    }

public function index()
{
    $videos = Video::all();

    return response()->json($videos);
}

}
