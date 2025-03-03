<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ParagraphController;
use App\Http\Controllers\VideoCatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductCatController;
use App\Http\Controllers\VideoLinkController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;






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
Route::get('videos', [VideoController::class, 'index']); 
Route::delete('video/{id}', [VideoController::class, 'destroy']);


///// images routes /////

Route::post('upload-image', [ImageController::class, 'uploadimage']); 
Route::get('/images', [ImageController::class, 'getAllImages']);
Route::get('show-image{id}',[ImageController::class,'showimage']);


/////// paragraphs routes /////

Route::post('/paragraphs', [ParagraphController::class, 'store']);
Route::get('paragraphs/{id}', [ParagraphController::class, 'showparagraph']);
Route::get('paragraphs', [ParagraphController::class, 'index']);
Route::put('paragraphs/{id}', [ParagraphController::class, 'update']);
Route::delete('paragraphs/{id}', [ParagraphController::class, 'destroy']);


// api.php
Route::get('/videocats', [VideocatController::class, 'index']); 
Route::post('/videocats', [VideocatController::class, 'store']); 

//products //
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products', function () {
    return response()->json(Product::all());
});

Route::post('/add-to-cart/{id}', [CartController::class, 'add']);
// Review //
Route::post('/rate-product', [ReviewController::class, 'store']);
Route::get('/ratings', [ReviewController::class, 'getAllRatings']);

Route::get('/categories', [ProductCatController::class, 'getCategories']);
Route::get('/products/category/{categoryId}', [ProductController::class, 'getProductsByCategory']);


// audio //
Route::post('audio', [AudioController::class, 'store']); 
Route::get('audio', [AudioController::class, 'index']);
Route::get('audio/{id}', [AudioController::class, 'show']); 
Route::get('audio/play/{filename}', [AudioController::class, 'play']);

// Reels //

Route::post('/reels', [ReelController::class, 'upload']); 
Route::get('/reels', [ReelController::class, 'index']); 
Route::delete('/reels/{id}', [ReelController::class, 'destroy']); 

// Order //
Route::get('/orders', [OrderController::class, 'show']);
Route::get('/user/{id}/orders', [OrderController::class, 'SpecificUser']);

Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);
Route::post('/place-order', [OrderController::class, 'placeOrder']);

Route::get('/category-images/{categoryId}', function ($categoryId) {
    $products = Product::where('category_id', $categoryId)->get();

    $allImages = [];

    foreach ($products as $product) {
        if ($product->images) {
            $imagesArray = json_decode($product->images, true);
            $allImages = array_merge($allImages, $imagesArray);
        }
    }

    return response()->json(['images' => $allImages]);
});
// video links //
Route::get('/video-links', [VideoLinkController::class, 'index']);  
Route::post('/video-links', [VideoLinkController::class, 'store']); 
Route::delete('/video-links/{id}', [VideoLinkController::class, 'destroy']); 
// review //
Route::post('/reviews', [ReviewController::class, 'store']);

Route::get('/reviews/{product_id}', function ($product_id) {
    $reviews = Review::where('product_id', $product_id)->latest()->get();

    $reviews = $reviews->map(function ($review) {
        $review->user_name = $review->user ? $review->user->name : "Anonymous";
        return $review ->makeHidden(['user']); 
    });

    return response()->json(['reviews' => $reviews]);
});

Route::get('/users/{id}', function ($id) {
    $user = User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    return response()->json($user);
});

//payment //
Route::post('/cash-on-delivery', [PaymentController::class, 'cashOnDelivery']);
Route::post('/upi-payment', [PaymentController::class, 'upiPayment']);

Route::get('/productdetails/{id}', [ProductController::class, 'details']);

Route::post('/register', [UserController::class, 'register']);
Route::get('/verify-email/{id}', [UserController::class, 'verifyEmail']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });
});

Route::post('/forgot-password', [UserController::class, 'sendOtp']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);


