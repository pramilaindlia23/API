<?php

namespace App\Http\Controllers;
use getID3;
use App\Models\AudioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    // Store audio file
// public function store(Request $request)
// {
//     try {
//         $request->validate([
//             'title' => 'required|string|max:255',
//             'artist_name' => 'nullable|string|max:255',
//             'audio' => 'required|mimes:mp3,wav,ogg|max:51200', 
//             'duration' => 'required|string', 
//         ]);
    
//         $path = $request->file('audio')->store('audio', 'public');
//         $filename = basename($path); 
       
//         $fullPath = storage_path("app/public/{$path}");
//         $getID3 = new getID3();
//         $fileInfo = $getID3->analyze($fullPath);
//         $durationSeconds = isset($fileInfo['playtime_seconds']) ? round($fileInfo['playtime_seconds']) : 0;
        
//         \Log::info('Audio duration:', ['duration' => $durationSeconds]); // Debugging

        
//         $formattedDuration = gmdate("i:s", $durationSeconds);

       
//         $audio = new AudioFile();
//         $audio->title = $request->title;
//         $audio->artist_name = $request->artist_name;
//         $audio->filename = $filename;  
//         $audio->path = $path;          
//         $audio->duration = $formattedDuration; 
//         $audio->save();

//         return response()->json(['message' => 'Audio uploaded successfully!', 'audio' => $audio], 201);

//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 400);
//     }
// }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'artist_name' => 'nullable|string|max:255',
        'audio' => 'required|file|mimes:mp3,wav,ogg',
        'duration' => 'required|integer', // Expecting milliseconds
    ]);

    $file = $request->file('audio');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('audio', $filename, 'public');

    $audio = new AudioFile();
    $audio->title = $request->title;
    $audio->artist_name = $request->artist_name;
    $audio->filename = $filename;
    $audio->path = $path;
    $audio->duration = $request->duration; 
    $audio->save();

    return response()->json(['message' => 'Audio uploaded successfully'], 201);
}

    // List all uploaded audio files
    public function index()
    {
        $audioFiles = AudioFile::all(); 
        return response()->json($audioFiles); 
    }

        public function show($id)
    {
        $audio = AudioFile::findOrFail($id);
        return response()->json($audio);
    }

    public function play($id)
{
    $audio = AudioFile::find($id);

    if (!$audio) {
        return response()->json(['error' => 'File not found'], 404);
    }

    return response()->json([
        'title' => $audio->title,
        'filename' => $audio->filename,
        'path' => asset("storage/{$audio->path}"), 
        'duration' => $audio->duration // Send duration
    ]);
}

}

