<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ParagraphController;
use App\Http\Controllers\VideoCatController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//// videos routes /////

Route::get('video-list', [VideoController::class, 'index']);
Route::get('video/{id}', [VideoController::class, 'show']);
Route::post('upload-video', [VideoController::class, 'upload']);
Route::get('videos', [VideoController::class, 'index']); // Fetch all videos
Route::delete('video/{id}', [VideoController::class, 'destroy']);


///// images routes /////

Route::post('upload-image', [ImageController::class, 'uploadimage']); 
Route::get('/images', [ImageController::class, 'getAllImages']);
Route::get('show-image{id}',[ImageController::class,'showimage']);


/////// paragraphs routes /////

Route::post('paragraph', [ParagraphController::class, 'store']); 
Route::get('paragraphs/{id}', [ParagraphController::class, 'showparagraph']);
Route::get('paragraphs', [ParagraphController::class, 'index']);
Route::put('paragraphs/{id}', [ParagraphController::class, 'update']);
Route::delete('paragraphs/{id}', [ParagraphController::class, 'destroy']);


// api.php

Route::post('videocategory', [VideoCatController::class, 'store']); 
Route::get('categories', [VideoCatController::class, 'index']);

//products //
Route::get('/products', [ProductController::class, 'index']);




