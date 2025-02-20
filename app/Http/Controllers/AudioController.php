<?php

namespace App\Http\Controllers;

use App\Models\AudioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    // Store audio file
    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|string|max:255', 
            'audio' => 'required|file|mimes:mp3,wav,ogg|max:10240', 
        ]);

        // Store the audio file in the 'public/audio' directory
        $path = $request->file('audio')->store('audio', 'public');
        $filename = basename($path);

        $audio = AudioFile::create([
            'title' => $request->input('title'), 
            'filename' => $filename,
            'path' => $path,
        ]);

        return response()->json([
            'message' => 'audio created successfully!',
            'audio' => $audio],200);
    }

    // List all uploaded audio files
    public function index()
    {
        $audioFiles = AudioFile::all(); 
        return response()->json($audioFiles); 
    }

    // Retrieve details of a specific audio file
    public function show($id)
    {
        $audio = AudioFile::findOrFail($id);
        return response()->json($audio);
    }

    // Play the uploaded audio file
    public function play($filename)
    {
        $path = storage_path('app/public/audio/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}

