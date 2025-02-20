<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paragraph;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class ParagraphController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }
    
        // Create new paragraph/event
        $paragraph = Paragraph::create([
            'title' => $request->title,
            'content' => $request->content,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
        ]);
    
        return response()->json(['message' => 'Event created successfully', 'event' => $paragraph], 201);
    }
    
public function index()
{
    // Fetch upcoming events sorted by date
    $paragraphs = Paragraph::orderBy('date', 'asc')->get();
    return response()->json($paragraphs);
}

    // public function store(Request $request)
    // {
    //     // Validate the incoming request
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|string|max:255',
    //         'content' => 'required|string|min:10', 
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
    //     }

    //     // Create the paragraph
    //     $paragraph = Paragraph::create([
    //         'title' => $request->title,
    //         'content' => $request->content,
    //     ]);

    //     return response()->json(['message' => 'Paragraph created successfully', 'paragraph' => $paragraph], 200);
    // }

    // public function index()
    // {
    //     // Get all paragraphs and return them as a JSON response
    //     $paragraphs = Paragraph::all();
    //     return response()->json($paragraphs);
    // }

    public function showparagraph($id)
    {
        $paragraph = Paragraph::find($id);
        if (!$paragraph) {
            return response()->json(['message' => 'Paragraph not found'], 404);
        }
        return response()->json($paragraph);
    }
    public function update(Request $request, $id)
    {
        $paragraph = Paragraph::find($id);
    
        if (!$paragraph) {
            return response()->json(['message' => 'Paragraph not found'], 404);
        }
    
        $paragraph->update($request->all());
    
        return response()->json(['message' => 'Paragraph updated successfully', 'paragraph' => $paragraph]);
    }
    public function destroy($id)
    {
        $paragraph = Paragraph::find($id);
        if (!$paragraph) {
            return response()->json(['message' => 'Paragraph not found'], 404);
        }

        $paragraph->delete();
        return response()->json(['message' => 'Paragraph deleted successfully']);
    }

}
