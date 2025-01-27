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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10', 
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        $paragraph = Paragraph::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Paragraph created successfully', 'paragraph' => $paragraph], 201);
    }

    public function showparagraph($id)
{
    $paragraph = Paragraph::find($id);

    if (!$paragraph) {
        return response()->json([
            'message' => 'Paragraph not found',
        ], Response::HTTP_NOT_FOUND);
    }

    return response()->json($paragraph);
}

public function index()
{
    $paragraphs = Paragraph::all();
    return response()->json($paragraphs);
}
}
