<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;

class ReelController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'video' => 'required|file|mimes:mp4,mov,avi|max:20000', 
    ]);

    try {
        $path = $request->file('video')->store('reels', 'public');
        $filename = basename($path); 
        
        $duration = $this->getVideoDuration(Storage::path($path)); 

        $url = url('storage/reels/' . $filename);

        $reel = Reel::create([
            'filename' => $filename,
            'path' => $path,
            'duration' => $duration,
        ]);

        return response()->json([
            'reel' => $reel,
            'video_url' => $url,
        ], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to upload video: ' . $e->getMessage()], 500);
    }
}

    private function getVideoDuration($videoPath)
    {
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($videoPath);
        $duration = $video->getFormat()->get('duration');
        return round($duration);  
    }

    // List all reels
    public function index()
    {
        $reels = Reel::all();
        foreach ($reels as $reel) {
            $reel->video_url = url('storage/reels/' . $reel->filename); 
        }
        return response()->json($reels);
    }
}

