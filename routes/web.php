<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\CheckoutController;



use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/// register Routes ///
Route::get('register',[UserController::class,'show'])->name('register');
Route::post('/register', [UserController::class, 'register']);

/// Login Routes ///
Route::get('login', [UserController::class, 'showlogin'])->name('login');
Route::post('login', [UserController::class, 'login']);

/// Dashboard Routes ///
Route::get('dashboard',[UserController::class,'dashboard'])->name('dashboard');
Route::middleware('auth')->get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

/// Users Routes ///
Route::get('userlist',function(){
    return view('userlist');
});

Route::get('/userlist', [UserController::class, 'index'])->name('userlist');
Route::get('/users', action: [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

/// Logout Routes ///
Route::post('logout', [UserController::class, 'logout'])->name('logout');

/// Video Uploads Routes ///
Route::get('upload',function(){
return view('videoupload');
})->name('videoupload');

/// Image Uploads Routes ///
Route::get('/upload-image', [ImageController::class, 'showForm'])->name('upload.form');

Route::get('/image-upload', function () {
    return view('imageupload');
})->name('imageupload');

/// Paragraph Uploads Routes ///
Route::get('paragraph',function(){
    return view('paragraphupload');
})->name('paragraphupload');


/// Category Route ///

Route::get('/create', [CategoryController::class, 'create'])->name('category.create');

Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/upload-image', [CategoryController::class, 'uploadImage'])->name('uploadImage');


Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.editcategory');

Route::post('/category/{id}/update', [CategoryController::class, 'update'])->name('category.update');

Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/video/edit/{id}', [VideoController::class, 'edit'])->name('videos.edit');
Route::put('/video/update/{id}', [VideoController::class, 'update'])->name('videos.update');
               

Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');


Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');

// product //
Route::get('/products', function () {
    return view('products.index');
});

// audio //
Route::view('audio/upload', 'audio.audioupload')->name('audio/upload');

//reels //

// Route::get('/upload-reel',function(){
//     return view('reels.reel')->name('/upload-reel');
// });
// Display the form (GET request)
Route::view('upload-reel', 'reels.reel')->name('upload-reel');
Route::post('upload-reel', [ReelController::class, 'upload'])->name('upload.reel');

// checkout //
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');


// Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show');




