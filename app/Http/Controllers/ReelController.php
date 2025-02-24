<?php

namespace App\Http\Controllers;

use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;

class ReelController extends Controller
{
    public function upload(Request $request)
{
    try {
        // Validate the uploaded file
        $validated = $request->validate([
            'reel' => 'required|file|mimes:mp4,mov,avi,mp3,wav,flac|max:9000000',
        ]);

        if ($request->hasFile('reel')) {
            $file = $request->file('reel');
            $filename = time() . '.' . $file->extension();
            $path = $file->storeAs('reels', $filename, 'public');

            // Store reel data in database (optional, if needed)
            $reel = Reel::create([
                'filename' => $filename,
                'path' => $path,
            ]);

            return response()->json([
                'message' => 'Reel uploaded successfully!',
                'video' => $reel,
                'video_url' => url('storage/' . $path),
            ], 201);
        } else {
            return response()->json(['message' => 'No file was uploaded.'], 400);
        }
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error uploading reel',
            'error' => $e->getMessage()
        ], 500);
    }
}
    
    // Get all reels
     public function index()
        {
            $reels = Reel::all();  
            return response()->json($reels); 
        }
            // Delete a reel
            public function destroy($id)
        {
            $reel = Reel::find($id);

            if (!$reel) {
                return response()->json(['message' => 'Reel not found'], 404);
            }

            Storage::delete('public/reels/' . $reel->path);

            $reel->delete();

            return response()->json(['message' => 'Reel deleted successfully']);
        }
}
