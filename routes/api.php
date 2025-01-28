<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ParagraphController;
use App\Http\Controllers\VideoCatController;

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
Route::post('upload-video', [VideoController::class, 'upload']);
Route::get('video-list', [VideoController::class, 'index']);
Route::get('video/{id}', [VideoController::class, 'show']);

///// images routes /////

Route::post('upload-image', [ImageController::class, 'uploadimage']); 
Route::get('/images', [ImageController::class, 'getAllImages']);
Route::get('show-image{id}',[ImageController::class,'showimage']);


/////// paragraphs routes /////

Route::post('paragraph', [ParagraphController::class, 'store']); 
Route::get('paragraph{id}',[ParagraphController::class,'showparagraph']);
Route::get('paragraphs', [ParagraphController::class, 'index']); 


// api.php

Route::post('videocategory', [VideoCatController::class, 'store']); // Create category
Route::get('categories', [VideoCatController::class, 'index']);

// Route::post('upload-video', [VideoController::class, 'upload']); // Video upload route
Route::get('videos', [VideoController::class, 'index']);
