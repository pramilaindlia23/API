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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;







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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard'); // Admin Dashboard View
    })->name('admin.dashboard');

    Route::post('/admin/update-role/{id}', [AdminController::class, 'updateRole'])->name('admin.update-role');
    Route::get('userlist',function(){
        return view('userlist');
    });
    Route::get('/userlist', [UserController::class, 'index'])->name('userlist');
    Route::get('/users', action: [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard'); 
    
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
});

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

// product //
Route::post('/category/store', [ProductCatController::class, 'store'])->name('category.store');
Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');

Route::get('category', function () {
    return view('products.index');  
})->name('category');

// audio //
Route::view('audio/upload', 'audio.audioupload')->name('audio/upload');

//reels //

Route::view('upload-reel', 'reels.reel')->name('upload-reel');
Route::post('upload-reel', [ReelController::class, 'upload'])->name('upload.reel');

// checkout //
Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    
    Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
    
    Route::get('/confirmation/{orderId}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
});
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
// Order //
Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
// products Category //
Route::get('/productCat', function () {
    return view('products.productCat');
});
Route::get('/productCat/{id}', [ProductController::class, 'productsByCategory'])->name('productsCat');
Route::get('/productCat/{id}', [ProductController::class, 'showCategoryProducts'])->name('category.products');
Route::get('/products/category/{categoryId}', [ProductController::class, 'getProductsByCategory']);

Route::get('/videolink', function () {
    return view('videolink.createvideolink');
});
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/email/verify/{id}', [UserController::class, 'verifyEmail'])->name('verification.verify');

Route::get('/forgot-password', function () {
    return view('password.forgot-password');
})->name('forgot-password');

