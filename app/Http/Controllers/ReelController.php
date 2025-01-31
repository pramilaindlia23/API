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
            // Validate the incoming request
            $validated = $request->validate([
                'filename' => 'required|string|max:255',
                'reel' => 'required|file|mimes:mp4,mov,avi|max:20000'  // Limit file size to 20MB
            ]);

            if ($request->hasFile('reel')) {
                $file = $request->file('reel');
                $filename = time() . '.' . $file->extension();  // Use a unique timestamp as the filename
                $path = $file->storeAs('reels', $filename, 'public');  // Store video in 'public' disk

                // Create a new Reel record
                $reel = Reel::create([
                    'filename' => $filename,
                    'path' => $path,
                ]);

                return response()->json([
                    'message' => 'Reel uploaded successfully!',
                    'video' => $reel,
                    'video_url' => url('storage/' . $path), // Return the URL to the video
                ], 201);
            } else {
                return response()->json(['message' => 'No Reel file uploaded.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error uploading Reel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $videos = Reel::all();  // Fetch all videos
        return response()->json($videos);  // Return the list of videos as JSON
    }

    public function destroy($id)
    {
        $reel = Reel::find($id);  // Find the reel by ID

        if (!$reel) {
            return response()->json([
                'message' => 'Video not found'
            ], 404);
        }

        // Delete the video file from storage
        Storage::delete('public/reels/' . $reel->path);

        // Delete the video record from the database
        $reel->delete();

        return response()->json([
            'message' => 'Video deleted successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request for update
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:video_cats,id', // Assuming you have a 'video_cats' table
        ]);

        $reel = Reel::find($id);  // Find the reel by ID

        if (!$reel) {
            return response()->json([
                'message' => 'Video not found',
            ], 404);
        }

        // Update the reel's title and category
        $reel->title = $validated['title'];
        $reel->category_id = $validated['category_id'];
        $reel->save();

        return response()->json([
            'message' => 'Video updated successfully!',
            'video' => $reel,
        ]);
    }

    public function show($id)
    {
        $reel = Reel::find($id);  // Find a specific reel by ID

        if (!$reel) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        return response()->json($reel);  // Return the specific reel's details
    }
}
